<?php
function solve($x_array) {
    global $Y_0, $h;
    $y = [$Y_0];

    # Runge-Kutta 4
    for ($i=1; ($i < 4)&&($i < count($x_array)); $i++) { 
        $k1 = f($x_array[$i-1], $y[$i-1])->mul($h);
        $k2 = f($x_array[$i-1] + $h/2, $y[$i-1]->add($k1->div(2)))->mul($h);
        $k3 = f($x_array[$i-1] + $h/2, $y[$i-1]->add($k2->div(2)))->mul($h);
        $k4 = f($x_array[$i-1] + $h, $y[$i-1]->add($k3))->mul($h);
        array_push($y, $y[$i-1]->add($k1->add($k2->mul(2))->add($k3->mul(2))->add($k4)->div(6)));
    }

    for ($i=4; $i < count($x_array); $i++) { 
        # predictor
        $pred_y = $y[$i-1]->add(
            f($x_array[$i-1], $y[$i-1])->mul(55)->sub(
                f($x_array[$i-2], $y[$i-2])->mul(59)
            )->add(
                f($x_array[$i-3], $y[$i-3])->mul(37)
            )->sub(
                f($x_array[$i-4], $y[$i-4])->mul(9)
            )->mul($h/24)
        );
        # Geer
        array_push($y, f($x_array[$i], $pred_y)->mul(12*$h)->add(
                $y[$i-1]->mul(48)
            )->sub(
                $y[$i-2]->mul(36)
            )->add(
                $y[$i-3]->mul(16)
            )->sub(
                $y[$i-4]->mul(3)
            )->div(25)
        );
    }

    return $y;
}
?>