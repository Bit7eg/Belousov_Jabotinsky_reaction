<?php
$FUNCTION = "test_1";
$METHOD = "euler";
$N = 20;

//  f()    $X_0    $X_N    $Y_0
require "functions/" . $FUNCTION . ".php";

require "methods/" . $METHOD . ".php";

$h = ($X_N - $X_0)/$N;

require "net.php";

$x = getNet();
$y = solve($x);
echo "{\n";
    echo "x: [" . $x[0];
    for ($i=1; $i < count($x); $i++) { 
        echo ", " . $x[$i];
    }
    echo "],\n";
    echo "y: [" . $y[0];
    for ($i=1; $i < count($y); $i++) { 
        echo ", " . $y[$i];
    }
    echo "]\n";
echo "}\n\n\n";

?>