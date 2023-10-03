let gl_utils = {};

gl_utils.CLIP_CONTROL_LH = false;

gl_utils.normalize = (vector) => {
    let length = vector.modulus();
    let coor_array = [];
    for (let index = 1; index <= vector.dimensions(); index++) {
        coor_array.push(vector.e(index)/length);
    }
    return $V(coor_array);
};

//veiw matrix getting
gl_utils.getViewMatrixRH = (cam_pos, cam_front, cam_up) => {
    let front = gl_utils.normalize(cam_front);
    let right = gl_utils.normalize(cam_up.cross(front));
    let up = front.cross(right);

    let result = $M([
        [right.e(1), up.e(1), -front.e(1), 0.0],
        [right.e(2), up.e(2), -front.e(2), 0.0],
        [right.e(3), up.e(3), -front.e(3), 0.0],
        [-right.dot(cam_pos), -up.dot(cam_pos), front.dot(cam_pos), 1.0],
    ]);
    return result;
};

gl_utils.getViewMatrixLH = (cam_pos, cam_front, cam_up) => {
    let front = gl_utils.normalize(cam_front);
    let right = gl_utils.normalize(front.cross(cam_up));
    let up = right.cross(front);

    let result = $M([
        [right.e(1), up.e(1), front.e(1), 0.0],
        [right.e(2), up.e(2), front.e(2), 0.0],
        [right.e(3), up.e(3), front.e(3), 0.0],
        [-right.dot(cam_pos), -up.dot(cam_pos), front.dot(cam_pos), 1.0],
    ]);
    return result;
};

gl_utils.getViewMatrix = (cam_pos, cam_front, cam_up) => {
    if (gl_utils.CLIP_CONTROL_LH) {
        return gl_utils.getViewMatrixLH(cam_pos, cam_front, cam_up);
    } else {
        return gl_utils.getViewMatrixRH(cam_pos, cam_front, cam_up);
    }
};

//perspective matrix getting
gl_utils.getPerspectiveMatrixRH = (fov, width, height, near, far) => {
    if (width <= 0) {
        console.log("Ширина области отображения должна быть положительной величиной");
        return null;
    }
    if (height <= 0) {
        console.log("Высота области отображения должна быть положительной величиной");
        return null;
    }
    if (fov <= 0) {
        console.log("Поле зрения должно быть положительной величиной");
        return null;
    }

    let h = Math.cos(0.5*fov) / Math.sin(0.5*fov);
    let w = h*height / width;   ///todo max(width , Height) / min(width , Height)?

    let result = $M([
        [w, 0.0, 0.0, 0.0],
        [0.0, h, 0.0, 0.0],
        [0.0, 0.0, -(far + near) / (far - near), -1.0],
        [0.0, 0.0, -(2*far*near) / (far - near), 0.0],
    ]);
    return result;
}

gl_utils.getPerspectiveMatrixLH = (fov, width, height, near, far) => {
    if (width <= 0) {
        console.log("Ширина области отображения должна быть положительной величиной");
        return null;
    }
    if (height <= 0) {
        console.log("Высота области отображения должна быть положительной величиной");
        return null;
    }
    if (fov <= 0) {
        console.log("Поле зрения должно быть положительной величиной");
        return null;
    }

    let h = Math.cos(0.5*fov) / Math.sin(0.5*fov);
    let w = h*height / width;   ///todo max(width , height) / min(width , height)?

    let result = $M([
        [w, 0.0, 0.0, 0.0],
        [0.0, h, 0.0, 0.0],
        [0.0, 0.0, (far + near) / (far - near), 1.0],
        [0.0, 0.0, -(2*far*near) / (far - near), 0.0],
    ]);
    return result;
}

gl_utils.getPerspectiveMatrix = (fov, width, height, near, far) => {
    if (gl_utils.CLIP_CONTROL_LH) {
        return gl_utils.getPerspectiveMatrixLH(fov, width, height, near, far);
    } else {
        return gl_utils.getPerspectiveMatrixRH(fov, width, height, near, far);
    }
}