<?php
function solve($x_array) {
    global $Y_0, $h;
    $y = [$Y_0];

    for ($i=1; $i < count($x_array); $i++) { 
        $k1 = $h*f($x_array[$i-1], $y[$i-1]);
        $k2 = $h*f($x_array[$i-1] + $h/2, $y[$i-1] + $k1/2);
        $k3 = $h*f($x_array[$i-1] + $h/2, $y[$i-1] + $k2/2);
        $k4 = $h*f($x_array[$i-1] + $h, $y[$i-1] + $k3);
        array_push($y, $y[$i-1] + ($k1 + 2*$k2 + 2*$k3 + $k4)/6);
    }

    return $y;
}
?>