<?php
# Adams–Bashforth–Moulton scheme
function solve($x_array) {
    global $Y_0, $h;
    $y = [$Y_0];

    # Runge-Kutta 4
    for ($i=1; ($i < 4)&&($i < count($x_array)); $i++) { 
        $k1 = f($x_array[$i-1], $y[$i-1])*$h;
        $k2 = f($x_array[$i-1] + $h/2, $y[$i-1] + $k1/2)*$h;
        $k3 = f($x_array[$i-1] + $h/2, $y[$i-1] + $k2/2)*$h;
        $k4 = f($x_array[$i-1] + $h, $y[$i-1] + $k3)*$h;
        array_push($y, $y[$i-1] + ($k1 + $k2*2 + $k3*2 + $k4)/6);
    }

    for ($i=4; $i < count($x_array); $i++) { 
        # predictor
        $pred_y = $y[$i-1] + (
            f($x_array[$i-1], $y[$i-1])*55 -
            f($x_array[$i-2], $y[$i-2])*59 +
            f($x_array[$i-3], $y[$i-3])*37 -
            f($x_array[$i-4], $y[$i-4])*9
        )*($h/24);
        # Adams method
        array_push($y, $y[$i-1] + (
            f($x_array[$i], $pred_y)*9 +
            f($x_array[$i-1], $y[$i-1])*19 -
            f($x_array[$i-2], $y[$i-2])*5 +
            f($x_array[$i-3], $y[$i-3])
        )*($h/24));
    }

    return $y;
}
?>