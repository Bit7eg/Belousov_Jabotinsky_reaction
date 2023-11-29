<?php
function solve($x_array) {
    global $Y_0, $h;
    $y = [$Y_0];

    for ($i=1; $i < count($x_array); $i++) { 
        $k1 = f($x_array[$i-1], $y[$i-1])->mul($h);
        $k2 = f($x_array[$i-1] + $h/2, $y[$i-1]->add($k1->div(2)))->mul($h);
        $k3 = f($x_array[$i-1] + $h/2, $y[$i-1]->add($k1->add($k2)->div(4)))->mul($h);
        $k4 = f($x_array[$i-1] + $h, $y[$i-1]->sub($k2)->add($k3->mul(2)))->mul($h);
        $k5 = f($x_array[$i-1] + 2*$h/3, $y[$i-1]->add($k1->mul(7)->add($k2->mul(10))->add($k4)->div(27)))->mul($h);
        $k6 = f($x_array[$i-1] + $h/5, $y[$i-1]->add($k1->mul(28)->sub($k2->mul(125))->add($k3->mul(546))->add($k4->mul(54))->sub($k5->mul(378))->div(625)))->mul($h);
        array_push($y, $y[$i-1]->add($k1->div(24)->add($k4->mul(5/48))->add($k5->mul(27/56))->add($k6->mul(125/336))));
    }

    return $y;
}
?>