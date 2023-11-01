<?php
function solve($x_array) {
    global $Y_0, $h;
    $y = [$Y_0];

    for ($i=1; $i < count($x_array); $i++) {
        $k1 = f($x_array[$i-1], $y[$i-1])*$h;
        $k2 = f($x_array[$i-1] + $h/2, $y[$i-1] + $k1/2)*$h;
        $k3 = f($x_array[$i-1] + $h, $y[$i-1] - $k1 + $k2*2)*$h;
        array_push($y, $y[$i-1] + ($k1 + $k2*4 + $k3)/6);
    }

    return $y;
}
?>