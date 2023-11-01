<?php
function solve($x_array) {
    global $Y_0, $h;
    $y = [$Y_0];

    for ($i=1; $i < count($x_array); $i++) { 
        $k1 = f($x_array[$i-1], $y[$i-1])*$h;
        $k2 = f($x_array[$i-1] + $h/2, $y[$i-1] + $k1/2)*$h;
        $k3 = f($x_array[$i-1] + $h/2, $y[$i-1] + ($k1 + $k2)/4)*$h;
        $k4 = f($x_array[$i-1] + $h, $y[$i-1] - $k2 + $k3*2)*$h;
        $k5 = f($x_array[$i-1] + 2*$h/3, $y[$i-1] + ($k1*7 + $k2*10 + $k4)/27)*$h;
        $k6 = f($x_array[$i-1] + $h/5, $y[$i-1] + ($k1*28 - $k2*125 + $k3*546 + $k4*54 - $k5*378)/625)*$h;
        array_push($y, $y[$i-1] + ($k1/24 + $k4*5/48 + $k5*27/56 + $k6*125/336));
    }

    return $y;
}
?>