<?php
$FUNCTION = $_GET["func"];
$METHOD = $_GET["algorithm"];

$h = ($X_N - $X_0);

//  f()    $X_0    $X_N    $Y_0
require "functions/" . $FUNCTION . ".php";

require "methods/" . $METHOD . ".php";

require "net.php";

echo "{\n";
    echo "\"errors\": [";
    $x = getNet();
    $y = solve($x);
    $error = abs($y[0] - phi($x[0]));
    for ($i=1; $i < count($x); $i++) { 
        $i_error = abs($y[$i] - phi($x[$i]));
        if ($error < $i_error) {
            $error = $i_error;
        }
    }
    $errors = "" . $error;
    $h /= 2;

    while ($h > 2**(-10)) {
        $x = getNet();
        $y = solve($x);
        $error = abs($y[0] - phi($x[0]));
        for ($i=1; $i < count($x); $i++) { 
            $i_error = abs($y[$i] - phi($x[$i]));
            if ($error < $i_error) {
                $error = $i_error;
            }
        }
        $errors = $error . ", " . $errors;
        $h /= 2;
    }
    echo $errors;
    echo "],\n";
echo "}\n";

?>