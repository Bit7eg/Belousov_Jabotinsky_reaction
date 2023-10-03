<?php
$X_0 = 0.0;
$X_N = 3.0;
$Y_0 = sin($X_0) + cos(2*$X_0);

function f(float $x, float $y): float {
    $phi = sin($x) + cos(2*$x);
    $der_phi = cos($x) - 2*sin(2*$x);
    $k = 2.0;

    return $der_phi + $k*($y - $phi);
}
?>