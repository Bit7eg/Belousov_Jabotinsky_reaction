<?php
$output = "";

$x = getNet();

for ($i=0; $i < count($x); $i++) { 
    $output = $output . $x[$i] . " " . phi($x[$i]) . "\n";
}
?>