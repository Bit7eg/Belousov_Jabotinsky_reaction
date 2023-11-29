<?php
function phi(float $x): float {
    return sin($x) + cos(2*pi()*$x);
}

$X_0 = 0.0;
$X_N = 1.0;
$Y_0 = phi($X_0);

function f(float $x, float $y): float {
    $der_phi = cos($x) - 2*pi()*sin(2*pi()*$x);
    $k = 6.0;

    return $der_phi + $k*($y - phi($x))**2;
}
?>