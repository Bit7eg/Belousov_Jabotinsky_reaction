let pointer_x, pointer_y, is_keydown = {}, is_canvas_focused = false;

async function updatePoints() {
    let verices = [];

    let func = document.getElementById("function").value,
        algorithm = document.getElementById("method").value,
        intervals = document.getElementById("intervals").value;

    let response = await (await fetch("http://" + window.location.host + `/api/solve.php?func=${func}&algorithm=${algorithm}&intervals=${intervals}`)).text();
    console.log(response);
    let {x, y} = JSON.parse(response);

    for (let i = 0; i < x.length; i++) {
        verices.push(x[i], y[i], 0.0);
    }

    loadVertices(verices);
    drawScene();
}

async function initCanvas(canvas) {
    await startDraw(canvas);
    await updatePoints();

    canvas.onpointerdown = event => {
        canvas.focus();
        canvas.setPointerCapture(event.pointerId);

        pointer_x = event.clientX;
        pointer_y = event.clientY;
        canvas.onpointermove = event => {
            let x_offset = (pointer_x - event.clientX)/viewSize.width;
            let y_offset = (pointer_y - event.clientY)/viewSize.height;
            pointer_x = event.clientX;
            pointer_y = event.clientY;

            camera.rotate(x_offset, y_offset);
            drawScene();
        };

        if ('onwheel' in document) {
            // IE9+, FF17+, Ch31+
            canvas.onwheel = onWheel;
        } else if ('onmousewheel' in document) {
            // устаревший вариант события
            canvas.onmousewheel = onWheel;
        } else {
            // Firefox < 17
            canvas.onMozMousePixelScroll = onWheel;
        }
          
        function onWheel(event) {
            var delta = event.deltaY || event.detail || event.wheelDelta;
            delta = delta/Math.abs(delta);
            camera.zoom += delta*camera.zoom_speed;
            drawScene();
          
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
        }
    };

    canvas.onlostpointercapture = event => {
        canvas.onpointermove = null;

        if ('onwheel' in document) {
            // IE9+, FF17+, Ch31+
            canvas.onwheel = null;
        } else if ('onmousewheel' in document) {
            // устаревший вариант события
            canvas.onmousewheel = null;
        } else {
            // Firefox < 17
            canvas.onMozMousePixelScroll = null;
        }
    };

    canvas.onfocus = event => {
        is_canvas_focused = true;

        canvas.onkeydown = canvas.onkeyup = event => {
            is_keydown[event.code] = event.type == 'keydown';
        };

        function gameCycle() {
            if (is_keydown["KeyW"]) {
                camera.move(Camera.direction.FORWARD);
            }
            if (is_keydown["KeyA"]) {
                camera.move(Camera.direction.LEFT);
            }
            if (is_keydown["KeyS"]) {
                camera.move(Camera.direction.BACKWARD);
            }
            if (is_keydown["KeyD"]) {
                camera.move(Camera.direction.RIGHT);
            }
            if (is_keydown["ShiftLeft"]) {
                camera.move(Camera.direction.UP);
            }
            if (is_keydown["ControlLeft"]) {
                camera.move(Camera.direction.DOWN);
            }
            drawScene();
            
            if (is_canvas_focused) {
                setTimeout(gameCycle, 0);
            }
        }
        gameCycle();
    };

    canvas.onblur = event => {
        is_canvas_focused = false;
        canvas.onkeydown = canvas.onkeyup = null;
    };

    drawScene();
}

function initPanel() {
    let update_button = document.getElementById("update");

    update_button.onclick = updatePoints;
}

window.onload = () => {
    let home_button = document.getElementById("home-button");
    let description_button = document.getElementById("description-button");
    let report_button = document.getElementById("report-button");
    let current_page = document.getElementsByTagName("main").item(0);

    let canvas = document.getElementById("glcanvas");
    if (canvas) initCanvas(canvas);

    let control_panel = document.getElementById("control-panel");
    if (control_panel) initPanel();

    home_button.onclick = async event => {
        let home_page = await (await fetch("http://" + window.location.host + "/pages/app.php")).text();
        home_page = new DOMParser().parseFromString(home_page, "text/html");
        home_page = home_page.getElementsByTagName("main").item(0);

        current_page.parentElement.replaceChild(home_page, current_page);
        current_page = document.getElementsByTagName("main").item(0);

        canvas = document.getElementById("glcanvas");
        if (canvas) initCanvas(canvas);

        let control_panel = document.getElementById("control-panel");
        if (control_panel) initPanel();
    }

    description_button.onclick = async event => {
        let description_page = await (await fetch("http://" + window.location.host + "/pages/description.php")).text();
        description_page = new DOMParser().parseFromString(description_page, "text/html");
        description_page = description_page.getElementsByTagName("main").item(0);

        current_page.parentElement.replaceChild(description_page, current_page);
        current_page = document.getElementsByTagName("main").item(0);
    }

    report_button.onclick = async event => {
        let report_page = await (await fetch("http://" + window.location.host + "/pages/report.php")).text();
        report_page = new DOMParser().parseFromString(report_page, "text/html");
        report_page = report_page.getElementsByTagName("main").item(0);

        current_page.parentElement.replaceChild(report_page, current_page);
        current_page = document.getElementsByTagName("main").item(0);
    }
}