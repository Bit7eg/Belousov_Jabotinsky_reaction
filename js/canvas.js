let gl, shaderProgram, vertexPositionAttribute, viewSize, graph, camera, axes;

let view_distanse = 100.0;
let gl_options = {
    antialias: true
};

async function startDraw(canvas) {
    viewSize = {
        width: canvas.getBoundingClientRect().width,
        height: canvas.getBoundingClientRect().height,
    };

    initWebGL(canvas);
    if (!gl) return;
    initSpace();
    graph = new Graph(gl.createBuffer());
    await initShaders();

    gl.clearColor(0.95, 0.95, 0.95, 1.0);
    gl.enable(gl.DEPTH_TEST);
    gl.depthFunc(gl.LEQUAL);
    gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);
}

function initWebGL(canvas) {
    gl = null;
    camera = new Camera();

    try {
        gl = canvas.getContext("webgl", gl_options) || canvas.getContext("experimental-webgl", gl_options);
    } catch (e) {}

    if (!gl) {
        alert("Неудаётся инициализировать WebGL. Возможно, браузер не поддерживает его.");
        camera = null;
        gl = null;
    }
}

function initSpace() {
    axes = [
        new Graph(gl.createBuffer()),
        new Graph(gl.createBuffer()),
        new Graph(gl.createBuffer())
    ];
    let color;
    for (let i = 0; i < 3; i++) {
        color = axes[i].color;
        color[i] = view_distanse;
        axes[i].color = color;

        axes[i].loadVertices([
             color[0]*  color[1],  color[2],
            -color[0], -color[1], -color[2]
        ], gl);
    }
}

function updateSpace() {
    let vertex1, vertex2;
    for (let i = 0; i < axes.length; i++) {
        vertex1 = vertex2 = [0.0, 0.0, 0.0];
        vertex1[i] = vertex2[i] = camera.position[i];
        vertex1[i] += view_distanse;
        vertex2[i] -= view_distanse;
        axes[i].loadVertices([
            vertex1[0], vertex1[1], vertex1[2],
            vertex2[0], vertex2[1], vertex2[2]
       ], gl);
    }
}

function loadVertices(vertices = []) {
    graph.loadVertices(vertices, gl);
}

function drawScene() {
    gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);

    setMatrixUniforms(
        gl_utils.getPerspectiveMatrix(camera.zoom, viewSize.width, viewSize.height, 0.01, view_distanse),
        camera.view_matrix
    );

    for (let i = 0; i < axes.length; i++) {
        setColorUniform(axes[i].color);

        gl.bindBuffer(gl.ARRAY_BUFFER, axes[i].verticesBuffer);
        gl.vertexAttribPointer(vertexPositionAttribute, 3, gl.FLOAT, false, 0, 0);
        gl.drawArrays(gl.LINE_STRIP, 0, axes[i].verticesNumber);
        gl.bindBuffer(gl.ARRAY_BUFFER, null);
    }

    setColorUniform(graph.color);
    gl.bindBuffer(gl.ARRAY_BUFFER, graph.verticesBuffer);
    gl.vertexAttribPointer(vertexPositionAttribute, 3, gl.FLOAT, false, 0, 0);
    gl.drawArrays(gl.LINE_STRIP, 0, graph.verticesNumber);
    gl.bindBuffer(gl.ARRAY_BUFFER, null);
}

async function initShaders() {
    let {vertexShader, fragmentShader} = await getShaders(gl, "shader");

    shaderProgram = gl.createProgram();
    gl.attachShader(shaderProgram, vertexShader);
    gl.attachShader(shaderProgram, fragmentShader);
    gl.linkProgram(shaderProgram);

    if (!gl.getProgramParameter(shaderProgram, gl.LINK_STATUS)) {
        alert("Неудаётся инициализировать шейдерную программу.");
    }

    gl.useProgram(shaderProgram);

    vertexPositionAttribute = gl.getAttribLocation(shaderProgram, "vertexPosition");
    gl.enableVertexAttribArray(vertexPositionAttribute);
}

async function getShaders(gl, shadername) {
    let vertexSource = await (await fetch("http://" + window.location.host + "/shaders/" + shadername + ".vert")).text();
    let fragmentSource = await (await fetch("http://" + window.location.host + "/shaders/" + shadername + ".frag")).text();

    let vertexShader = gl.createShader(gl.VERTEX_SHADER);
    let fragmentShader = gl.createShader(gl.FRAGMENT_SHADER);

    gl.shaderSource(vertexShader, vertexSource);
    gl.shaderSource(fragmentShader, fragmentSource);

    gl.compileShader(vertexShader);
    if (!gl.getShaderParameter(vertexShader, gl.COMPILE_STATUS)) {
        alert("Произошла ошибка при компиляции вершинного шейдера: " + gl.getShaderInfoLog(vertexShader));
        vertexShader = null;
    }

    gl.compileShader(fragmentShader);
    if (!gl.getShaderParameter(fragmentShader, gl.COMPILE_STATUS)) {
        alert("Произошла ошибка при компиляции фрагментного шейдера: " + gl.getShaderInfoLog(fragmentShader));
        fragmentShader = null;
    }

    return {vertexShader, fragmentShader};
}

function setMatrixUniforms(pMatrix, mvMatrix) {
    let pUniform = gl.getUniformLocation(shaderProgram, "uPMatrix");
    gl.uniformMatrix4fv(
        pUniform,
        false,
        new Float32Array([
            pMatrix.e(1, 1), pMatrix.e(1, 2), pMatrix.e(1, 3), pMatrix.e(1, 4),
            pMatrix.e(2, 1), pMatrix.e(2, 2), pMatrix.e(2, 3), pMatrix.e(2, 4),
            pMatrix.e(3, 1), pMatrix.e(3, 2), pMatrix.e(3, 3), pMatrix.e(3, 4),
            pMatrix.e(4, 1), pMatrix.e(4, 2), pMatrix.e(4, 3), pMatrix.e(4, 4)
        ])
    );

    let mvUniform = gl.getUniformLocation(shaderProgram, "uMVMatrix");
    gl.uniformMatrix4fv(
        mvUniform,
        false,
        new Float32Array([
            mvMatrix.e(1, 1), mvMatrix.e(1, 2), mvMatrix.e(1, 3), mvMatrix.e(1, 4),
            mvMatrix.e(2, 1), mvMatrix.e(2, 2), mvMatrix.e(2, 3), mvMatrix.e(2, 4),
            mvMatrix.e(3, 1), mvMatrix.e(3, 2), mvMatrix.e(3, 3), mvMatrix.e(3, 4),
            mvMatrix.e(4, 1), mvMatrix.e(4, 2), mvMatrix.e(4, 3), mvMatrix.e(4, 4)
        ])
    );
}

function setColorUniform(color) {
    let colorUniform = gl.getUniformLocation(shaderProgram, "color");
    gl.uniform4fv(
        colorUniform,
        new Float32Array(color)
    );
}