<?php
function solve($x_array) {
    global $Y_0, $h;
    $y = [$Y_0];

    # Runge-Kutta 4
    for ($i=1; $i < 4; $i++) { 
        $k1 = $h*f($x_array[$i-1], $y[$i-1]);
        $k2 = $h*f($x_array[$i-1] + $h/2, $y[$i-1] + $k1/2);
        $k3 = $h*f($x_array[$i-1] + $h/2, $y[$i-1] + $k2/2);
        $k4 = $h*f($x_array[$i-1] + $h, $y[$i-1] + $k3);
        array_push($y, $y[$i-1] + ($k1 + 2*$k2 + 2*$k3 + $k4)/6);
    }

    # predictor from Adams–Bashforth–Moulton scheme
    for ($i=4; $i < count($x_array); $i++) {
        array_push($y, $y[$i-1] + ($h/24)*(
            55*f($x_array[$i-1], $y[$i-1]) -
            59*f($x_array[$i-2], $y[$i-2]) +
            37*f($x_array[$i-3], $y[$i-3]) -
            9*f($x_array[$i-4], $y[$i-4])
        ));
    }

    return $y;
}
?>