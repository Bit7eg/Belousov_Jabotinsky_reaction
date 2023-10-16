<?php
function phi(float $x): float {
    return sin(2*$x) + cos($x);
}

$X_0 = 1.0;
$X_N = 10.0;
$Y_0 = phi($X_0);

function f(float $x, float $y): float {
    $der_phi = 2*cos(2*$x) - sin($x);
    $k = 1.0;

    return $der_phi + $k*($y - phi($x));
}
?>