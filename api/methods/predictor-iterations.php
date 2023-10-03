<?php
function solve($x_array) {
    global $Y_0, $h;
    $lambda = 0.001;

    $y = [$Y_0];
    $buff = [$Y_0];
    $is_solved = true;

    # Euler
    for ($i=1; $i < count($x_array); $i++) { 
        array_push($buff, $buff[$i-1] + $h*f($x_array[$i-1], $buff[$i-1]));
        array_push($y, $y[$i-1] + ($h/2)*(f($x_array[$i-1], $y[$i-1]) + f($x_array[$i], $buff[$i])));
        if (abs($y[$i] - $buff[$i])/abs($buff[$i]) > $lambda) {
            $is_solved = false;
        }
    }

    # Adams iterations
    while (!$is_solved) {
        $is_solved = true;
        for ($i=1; $i < count($x_array); $i++) { 
            $buff[$i] = $y[$i];
            $y[$i] = $y[$i-1] + ($h/2)*(f($x_array[$i-1], $y[$i-1]) + f($x_array[$i], $buff[$i]));
            if (abs($y[$i] - $buff[$i])/abs($buff[$i]) > $lambda) {
                $is_solved = false;
            }
        }
    }

    return $y;
}
?>