<?php
$X_0 = 0.0;
$X_N = 1.0;
$Y_0 = sin($X_0) + cos(2*pi()*$X_0);

function f(float $x, float $y): float {
    $phi = sin($x) + cos(2*pi()*$x);
    $der_phi = cos($x) - 2*pi()*sin(2*pi()*$x);
    $k = 6.0;

    return $der_phi + $k*($y - $phi);
}
?>