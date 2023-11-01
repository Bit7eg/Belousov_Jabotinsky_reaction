<?php
$FUNCTION = "test-1";
$METHOD = "adams-bashforth-moulton";
$N = 64;
/* возможные:
    "solution",
    "solve",
    "errors", */
$scripts = [
    "errors",
];

//  f()    $X_0    $X_N    $Y_0
require "functions/" . $FUNCTION . ".php";

require "methods/" . $METHOD . ".php";

$h = ($X_N - $X_0)/$N;

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

if (in_array("solution", $scripts)) {
    include "solution.php";
    file_put_contents("plots/" . $FUNCTION . "_solution.gpl", $output);
}

if (in_array("solve", $scripts)) {
    for (; $N < 2**15; $N*=2) { 
        $h = ($X_N - $X_0)/$N;
        include "solve.php";
        file_put_contents("plots/" . $FUNCTION . "_" . $METHOD . "_" . $N . "_solve.gpl", $output);
    }
}

if (in_array("errors", $scripts)) {
    include "errors.php";
    file_put_contents("plots/" . $FUNCTION . "_" . $METHOD . "_errors.gpl", $output);
    file_put_contents("orders/" . $FUNCTION . "_" . $METHOD . "_orders.txt", $order_output);
    file_put_contents("runge-errors/" . $FUNCTION . "_" . $METHOD . "_errors.txt", $runge_output);
}
?>