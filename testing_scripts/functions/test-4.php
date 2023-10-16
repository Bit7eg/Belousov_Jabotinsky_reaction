<?php
function phi(float $x): float {
    return sin(2*$x) - log($x);
}

$X_0 = 1.0;
$X_N = 6.0;
$Y_0 = phi($X_0);

function f(float $x, float $y): float {
    $der_phi = 2*cos(2*$x) - 1/$x;
    $k = 3.0;

    return $der_phi + $k*($y - phi($x));
}
?>