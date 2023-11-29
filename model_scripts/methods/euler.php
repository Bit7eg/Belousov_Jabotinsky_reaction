<?php
function solve($x_array) {
    global $Y_0, $h;
    $y = [$Y_0];

    for ($i=1; $i < count($x_array); $i++) { 
        array_push($y, $y[$i-1]->add(f($x_array[$i-1], $y[$i-1])->mul($h)));
    }

    return $y;
}
?>