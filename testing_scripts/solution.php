<?php
$output = "";
$h = ($X_N - $X_0)/(10**4);

for ($x=$X_0; $x <= $X_N; $x+=$h) { 
    $output = $output . $x . " " . phi($x) . "\n";
}

$h = ($X_N - $X_0)/$N;
?>