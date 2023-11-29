<?php
$output = "";
$order_output = "";
$runge_output = "";
$errors_output = "";
$plot_output = "";
$errors = [];
$x_buff = [];
$y_buff = [];
$errors_num = 0;
$y_max = 0;
$h = $X_N - $X_0;
$theory_orders = [
    "euler" => 1,
    "iterations" => 2,
    "runge-kutta3" => 3,
    "geer4" => 4,
    "adams-bashforth-moulton" => 4,
    "adams" => 4,
    "runge-kutta4" => 4,
    "runge-kutta5" => 5,
];

require_once "get_zero.php";
$EPSILON = getZero();

$x = getNet();
$y = solve($x);
$error = abs($y[0] - phi($x[0]));
$y_max = abs(phi($x[0]));
for ($i=1; $i < count($x); $i++) { 
    $i_error = abs($y[$i] - phi($x[$i]));
    if ($error < $i_error) {
        $error = $i_error;
    }

    $i_y = abs(phi($x[$i]));
    if ($y_max < $i_y) {
        $y_max = $i_y;
    }
}
$errors_num = array_push($errors, $error);
$h /= 2;

while ($h > $EPSILON && $errors_num < 20) {
    $x_buff = $x;
    $y_buff = $y;
    $x = getNet();
    $y = solve($x);
    $error = abs($y[0] - phi($x[0]));
    $runge_error = (2**$theory_orders[$METHOD])*abs($y[0] - $y_buff[0])/(2**$theory_orders[$METHOD] - 1);
    for ($i=1; $i < count($y_buff); $i++) { 
        $i_error = abs($y[$i] - phi($x[$i]));
        if ($error < $i_error) {
            $error = $i_error;
        }

        $i_runge_error = (2**$theory_orders[$METHOD])*abs($y[2*$i] - $y_buff[$i])/(2**$theory_orders[$METHOD] - 1);
        if ($runge_error < $i_runge_error) {
            $runge_error = $i_runge_error;
        }
    }
    for ($i=count($y_buff); $i < count($x); $i++) {
        $i_error = abs($y[$i] - phi($x[$i]));
        if ($error < $i_error) {
            $error = $i_error;
        }
    }
    $errors_num = array_push($errors, $error);

    $runge_output = $runge_output . count($y)-1 . " " . $runge_error . "\n";
    $plot_output = $plot_output . log(count($y)-1, 10) . " " . log($runge_error, 10) . "\n";

    $h /= 2;
}

$output = log(2, 10) . " " . log($errors[0], 10) . "\n";
$errors_output = "2 " . $errors[0] . "\n";
for ($i = 1; $i < $errors_num; $i++) { 
    $order_output = $order_output . 2**($i) . " " . abs(log($errors[$i-1]/$errors[$i], 2)) . "\n";

    $output = $output . log(2**($i+1), 10) . " " . log($errors[$i], 10) . "\n";
    $errors_output = $errors_output . 2**($i+1) . " " . $errors[$i] . "\n";
}

$h = ($X_N - $X_0)/$N;
?>