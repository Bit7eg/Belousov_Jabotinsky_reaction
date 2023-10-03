<?php
$X_0 = 1.0;
$X_N = 10.0;
$Y_0 = sin(log($X_0)) + cos(pi()*$X_0);

function f(float $x, float $y): float {
    $phi = sin(log($x)) + cos(pi()*$x);
    $der_phi = cos(log($x))/$x - pi()*sin(pi()*$x);
    $k = 2.0;

    return $der_phi + $k*($y - $phi);
}
?>