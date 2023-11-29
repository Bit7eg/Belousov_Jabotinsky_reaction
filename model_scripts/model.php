<?php
include_once "../vendor/autoload.php";
require_once "../vector.php";
require_once "../list.php";

/* возможные:
    "adams-bashforth-moulton",  131072
    "runge-kutta4",     65536
    "runge-kutta5",     65536
*/
$METHOD = "adams-bashforth-moulton";
$X_FIRST = 0.0;
$X_LAST = 300.0;
$gN = (2**14)*((int) ceil($X_LAST - $X_FIRST));
$N = 4;
$correct = (int) ($N*$gN/50000);
/* возможные:
    "solution",
    "solve",
    "errors", */
$scripts = [
    "errors",
];

$X_0 = $X_FIRST;
$X_N = $X_0 + ($X_LAST - $X_FIRST)/$gN;
$Y_0 = new Vector([1, 1, 1]);

function f(float $x, Vector $y): Vector {
    $k1 = 77.27*(10**(0));
    $k2 = 8.375*(10**(-6));
    $k3 = 1/77.27*(10**(0));
    $k4 = 0.161*(10**(0));

    return new Vector([
        $k1*($y[1] - $y[0]*$y[1] + $y[0] - $k2*($y[0]*$y[0])),
        $k3*(-$y[1] - $y[0]*$y[1] + $y[2]),
        $k4*($y[0] - $y[2])
    ]);
}

require "methods/" . $METHOD . ".php";

function getNet() {
    global $X_0, $X_N, $N, $h;
    $h = ($X_N - $X_0)/$N;
    $x = [$X_0];
    $i = 0;

    while ($x[$i] < $X_N) {
        $i += 1;
        array_push($x, $h*$i + $X_0);
    }

    return $x;
}

if (in_array("solve", $scripts)) {
    $output = "";
    $output1 = "";
    $output2 = "";
    $output3 = "";
    for ($i=0; $i < $gN; $i++) { 
        $x = getNet();
        $y = solve($x);

        $X_0 = $X_N;
        $X_N = $X_0 + ($X_LAST - $X_FIRST)/$gN;
        $Y_0 = $y[$N];

        for ($j=0; $j < count($x); $j++) { 
            if (($correct == 0)||(($i*count($x)+$j)%$correct == 0)) {
                $output .= $y[$j][0] . " " . $y[$j][1] . " " . $y[$j][2] . "\n";
                $output1 .= $x[$j] . " " . $y[$j][0] . "\n";
                $output2 .= $x[$j] . " " . $y[$j][1] . "\n";
                $output3 .= $x[$j] . " " . $y[$j][2] . "\n";
            }
        }
        echo 1 . " " . (int)$i/$gN*100 . "\n";
    }
    echo "\n\n\n";
    file_put_contents("model/" . $METHOD . "_solve21.gpl", $output);
    #file_put_contents("model/" . $METHOD . "_solve6-1.gpl", $output1);
    #file_put_contents("model/" . $METHOD . "_solve6-2.gpl", $output2);
    #file_put_contents("model/" . $METHOD . "_solve6-3.gpl", $output3);
}

if (in_array("errors", $scripts)) {
    $output = "";
    $theory_orders = [
        "adams-bashforth-moulton" => 4,
        "adams" => 4,
        "runge-kutta4" => 4,
        "runge-kutta5" => 5,
    ];

    require_once "get_zero.php";
    $EPSILON = getZero();

    file_put_contents("data/" . $METHOD . "_errors.gpl", "");
    $k = 1;
    while (($X_N - $X_0)/$N > $EPSILON) {
        $error = 0;
        $X_0 = $X_FIRST;
        $X_N = $X_0 + ($X_LAST - $X_FIRST)/$gN;
        $Y_0 = new Vector([4.0, 1.1, 4.0]);
        $Y_0_BUFF = new Vector([4.0, 1.1, 4.0]);

        for ($i=0; $i <= $gN; $i++) {
            $x = getNet();
            $y_buff = solve($x);
            $N *= 2;
            $Y_0 = $Y_0_BUFF;
            
            $x = getNet();
            $y = solve($x);
            $N /= 2;

            $X_0 = $X_N;
            $X_N = $X_0 + ($X_LAST - $X_FIRST)/$gN;
            $Y_0 = $y_buff[$N];
            $Y_0_BUFF = $y[2*$N];

            for ($j=0; $j < count($y_buff); $j++) { 
                $j_error = (2**$theory_orders[$METHOD])*norm($y[2*$j]->sub($y_buff[$j]))/(2**$theory_orders[$METHOD] - 1);
                if ($error < $j_error) {
                    $error = $j_error;
                }
            }
            if (in_array(null, $y) || in_array(null, $y_buff)) {
                $error = null;
                break;
            }
            echo 1+$k . " " . (int)$i/$gN*100 . "\n";
        }
        $gN *= 2;
        $k++;
        file_put_contents("data/" . $METHOD . "_errors.gpl", $gN*$N . " " . $error . "\n", FILE_APPEND);
    }
}
?>