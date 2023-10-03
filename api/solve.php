<?php
$FUNCTION = $_GET["func"];
$METHOD = $_GET["algorithm"];
$N = $_GET["intervals"];

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

$x = getNet();
$y = solve($x);
echo "{\n";
    echo "\"x\": [" . $x[0];
    for ($i=1; $i < count($x); $i++) { 
        echo ", " . $x[$i];
    }
    echo "],\n";
    echo "\"y\": [" . $y[0];
    for ($i=1; $i < count($y); $i++) { 
        echo ", " . $y[$i];
    }
    echo "]\n";
echo "}\n";

?>