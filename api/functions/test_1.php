<?php
$X_0 = 1.0;
$X_N = 5.0;
$Y_0 = exp(1/$X_0) + cos(2*$X_0);

function f(float $x, float $y): float {
    $phi = exp(1/$x) + cos(2*$x);
    $der_phi = exp(1/$x)/($x*$x) - 2*sin(2*$x);
    $k = 2.0;

    return $der_phi + $k*($y - $phi);
}
?>