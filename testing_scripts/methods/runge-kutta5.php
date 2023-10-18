<?php
function solve($x_array) {
    global $Y_0, $h;
    $y = [$Y_0];

    for ($i=1; $i < count($x_array); $i++) { 
        $k1 = $h*f($x_array[$i-1], $y[$i-1]);
        $k2 = $h*f($x_array[$i-1] + $h/2, $y[$i-1] + $k1/2);
        $k3 = $h*f($x_array[$i-1] + $h/2, $y[$i-1] + ($k1 + $k2)/4);
        $k4 = $h*f($x_array[$i-1] + $h, $y[$i-1] - $k2 + 2*$k3);
        $k5 = $h*f($x_array[$i-1] + 2*$h/3, $y[$i-1] + (7*$k1 + 10*$k2 + $k4)/27);
        $k6 = $h*f($x_array[$i-1] + $h/5, $y[$i-1] + (28*$k1 - 125*$k2 + 546*$k3 + 54*$k4 - 378*$k5)/625);
        array_push($y, $y[$i-1] + ($k1/24 + 5*$k4/48 + 27*$k5/56 + 125*$k6/336));
    }

    return $y;
}
?>