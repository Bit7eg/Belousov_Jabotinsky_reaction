class Camera {
    _state = {
        position: $V([0.0, 0.0, -5.0]),
        front_direction: $V([0.0, 0.0, 1.0]),
        up_direction: $V([0.0, 1.0, 0.0]),
        right_direction: $V([1.0, 0.0, 0.0]),
        yaw: Math.PI/2,
        pitch: 0.0,
        speed: 5.0,
        sensitivity: 2.0,
        zoom: Math.PI/4,
        zoom_speed: 0.1,
        deltaTime: 0,
    };

    static direction = {
        FORWARD: 1,
        BACKWARD: 2,
        LEFT: 3,
        RIGHT: 4,
        UP: 5,
        DOWN: 6
    };

    constructor() {
        let lastUpdateTime = 0;
        const camera_link = this;
        function countDelay(now) {
            now *= 0.001;
            camera_link._state.deltaTime = now - lastUpdateTime;
            lastUpdateTime = now;

            requestAnimationFrame(countDelay);
        }
        requestAnimationFrame(countDelay);
    }

    //helping methods
    updateVectors() {
        this._state.front_direction = gl_utils.normalize($V([
            Math.cos(this.yaw)*Math.cos(this.pitch),
            Math.sin(this.pitch),
            Math.sin(this.yaw)*Math.cos(this.pitch)
        ]));
        this._state.right_direction = gl_utils.normalize($V([0.0, 1.0, 0.0]).cross(this.front));
        this._state.up_direction = gl_utils.normalize(this.front.cross(this.right));
    }

    //getters and seters
    get position() {
        return this._state.position;
    }
    set position(value) {
        this._state.position = value;
    }

    get front() {
        return this._state.front_direction;
    }

    get up() {
        return this._state.up_direction;
    }

    get right() {
        return this._state.right_direction;
    }

    get yaw() {
        return this._state.yaw;
    }
    set yaw(value) {
        this._state.yaw = value;
        this.updateVectors();
    }

    get pitch() {
        return this._state.pitch;
    }
    set pitch(value) {
        this._state.pitch = value;
        if (this.pitch > Math.PI/2 - 0.01) {
            this._state.pitch = Math.PI/2 - 0.01;
        } else if (this.pitch < 0.01 - Math.PI/2) {
            this._state.pitch = 0.01 - Math.PI/2;
        }
        this.updateVectors();
    }

    get speed() {
        return this._state.speed;
    }
    set speed(value) {
        if (value > 0) {
            this._state.speed = value;
        }
    }

    get sensitivity() {
        return this._state.sensitivity;
    }
    set sensitivity(value) {
        if (value > 0) {
            this._state.sensitivity = value;
        }
    }

    get zoom() {
        return this._state.zoom;
    }
    set zoom(value) {
        this._state.zoom = value;
        if (this.zoom > Math.PI/2) {
            this._state.zoom = Math.PI/2;
        } else if (this.zoom < 0.01) {
            this._state.zoom = 0.01;
        }
    }

    get zoom_speed() {
        return this._state.zoom_speed;
    }
    set zoom_speed(value) {
        if (value > 0) {
            this._state.zoom_speed = value;
        }
    }

    get frame_period() {
        return this._state.deltaTime;
    }

    get view_matrix() {
        return gl_utils.getViewMatrix(this.position, this.front, this.up);
    }

    //other methods
    move(direction) {
        let velocity = this.speed*this.frame_period;
        if (direction === Camera.direction.FORWARD) {
            this.position = this.position.add(gl_utils.normalize($V([this.front.e(1), 0.0, this.front.e(3)])).multiply(velocity));
        } else if (direction === Camera.direction.BACKWARD) {
            this.position = this.position.add(gl_utils.normalize($V([this.front.e(1), 0.0, this.front.e(3)])).multiply(-velocity));
        } else if (direction === Camera.direction.LEFT) {
            this.position = this.position.add(this.right.multiply(-velocity));
        } else if (direction === Camera.direction.RIGHT) {
            this.position = this.position.add(this.right.multiply(velocity));
        } else if (direction === Camera.direction.UP) {
            this.position = this.position.add($V([0.0, 1.0, 0.0]).multiply(velocity));
        } else if (direction === Camera.direction.DOWN) {
            this.position = this.position.add($V([0.0, 1.0, 0.0]).multiply(-velocity));
        }
    };

    rotate(horizontal_angle, vertical_angle) {
        let h_angle = horizontal_angle * this.sensitivity;
        let v_angle = vertical_angle * this.sensitivity;

        this.yaw += h_angle;
        this.pitch += v_angle;

        this.updateVectors();
    };
}