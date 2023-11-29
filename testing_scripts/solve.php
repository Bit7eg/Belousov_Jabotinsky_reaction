<?php
$output = "";

$x = getNet();
$time = microtime(true);
$y = solve($x);
$time = microtime(true) - $time;

for ($i=0; $i < count($x); $i++) { 
    $output = $output . $x[$i] . " " . $y[$i] . "\n";
}
?>