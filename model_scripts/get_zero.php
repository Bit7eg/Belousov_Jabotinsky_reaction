<?php
function getZero(): float {
    $zero = 1.0;
    $next_zero = $zero/2;
    while ($next_zero != $next_zero/2) {
        $zero = $next_zero;
        $next_zero /= 2;
    }
    return $zero;
}
?>