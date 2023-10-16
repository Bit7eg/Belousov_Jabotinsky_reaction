<?php
$output = "";

$x = getNet();
$y = solve($x);

for ($i=0; $i < count($x); $i++) { 
    $output = $output . $x[$i] . " " . $y[$i] . "\n";
}
?>