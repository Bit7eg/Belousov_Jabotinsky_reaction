let gl, shaderProgram, vertexPositionAttribute, viewSize, verticesBuffer, verticesNumber, camera;

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
    verticesBuffer = gl.createBuffer();
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

function loadVertices(vertices = []) {
    gl.bindBuffer(gl.ARRAY_BUFFER, verticesBuffer);

    verticesNumber = vertices.length/3;

    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.DYNAMIC_DRAW);
}

function drawScene() {
    gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);

    setMatrixUniforms(
        gl_utils.getPerspectiveMatrix(camera.zoom, viewSize.width, viewSize.height, 0.01, 100.0),
        camera.view_matrix
    );

    gl.bindBuffer(gl.ARRAY_BUFFER, verticesBuffer);
    gl.vertexAttribPointer(vertexPositionAttribute, 3, gl.FLOAT, false, 0, 0);
    gl.drawArrays(gl.LINE_STRIP, 0, verticesNumber);
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