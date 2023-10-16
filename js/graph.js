class Graph {
    _state = {
        verticesBuffer: null,
        verticesNumber: 0,
        color: [0.0, 0.0, 0.0, 1.0],
    };

    constructor(buffer) {
        this._state.verticesBuffer = buffer;
    }

    get verticesBuffer() {
        return this._state.verticesBuffer;
    }

    get verticesNumber() {
        return this._state.verticesNumber;
    }

    get color() {
        return this._state.color;
    }
    set color(value = []) {
        for (let i = 0; i < value.length; i++) {
            if (value[i] > 1.0) {
                value[i] = 1.0;
            } else if (value[i] < 0.0) {
                value[i] = 0.0;
            }
        }
        this._state.color = value;
    }

    loadVertices(vertices = [], gl) {
        gl.bindBuffer(gl.ARRAY_BUFFER, this._state.verticesBuffer);

        this._state.verticesNumber = vertices.length/3;

        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.DYNAMIC_DRAW);
        gl.bindBuffer(gl.ARRAY_BUFFER, null);
    }
};