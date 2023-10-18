<?php
$output = "";
$order_output = "";
$errors = [];
$errors_num = 0;
$h = $X_N - $X_0;

require_once "get_zero.php";
$EPSILON = getZero();

while ($h > $EPSILON && $errors_num < 20) {
    $x = getNet();
    $y = solve($x);
    $error = abs($y[0] - phi($x[0]));
    for ($i=1; $i < count($x); $i++) { 
        $i_error = abs($y[$i] - phi($x[$i]));
        if ($error < $i_error) {
            $error = $i_error;
        }
    }
    $errors_num = array_push($errors, $error);
    $h /= 2;
}

$h = $X_N - $X_0;
$output = $h . " " . $errors[0] . "\n";
$h /= 2;
for ($i = 1; $i < $errors_num; $i++) { 
    $order_output = $order_output . $h . " " . log($errors[$i-1]/$errors[$i], 2) . "\n";
    
    $output = $output . $h . " " . $errors[$i] . "\n";
    $h /= 2;
}

$h = ($X_N - $X_0)/$N;
?>