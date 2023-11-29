<?php
function solve($x_array) {
    global $Y_0, $h;
    $y = [$Y_0];

    for ($i=1; $i < count($x_array); $i++) {
        $k1 = f($x_array[$i-1], $y[$i-1])->mul($h);
        $k2 = f($x_array[$i-1] + $h/2, $y[$i-1]->add($k1->div(2)))->mul($h);
        $k3 = f($x_array[$i-1] + $h, $y[$i-1]->sub($k1)->add($k2->div(2)))->mul($h);
        array_push($y, $y[$i-1]->add($k1->add($k2->mul(4))->add($k3)->div(6)));
    }

    return $y;
}
?>