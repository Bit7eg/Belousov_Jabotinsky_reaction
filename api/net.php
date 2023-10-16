<?php
function getNet() {
    global $X_0, $X_N, $h;
    $x = [$X_0];
    $i = 0;

    while ($x[$i] < $X_N) {
        $i += 1;
        array_push($x, $h*$i + $X_0);
    }

    return $x;
}
?>