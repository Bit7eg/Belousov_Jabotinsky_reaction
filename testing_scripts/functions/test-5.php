<?php
function phi(float $x): float {
    return sin(log($x)) + cos(pi()*$x);
}

$X_0 = 1.0;
$X_N = 9.0;
$Y_0 = phi($X_0);

function f(float $x, float $y): float {
    $der_phi = cos(log($x))/$x - pi()*sin(pi()*$x);
    $k = 2.0;

    return $der_phi + $k*($y - phi($x))**2;
}
?>