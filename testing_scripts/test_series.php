<?php
$test_configs = array(
    [
        "function" => "test-1",
        "method" => "euler",
        "intervals_number" => 20,
    ],
);

require "test_function.php";

foreach ($test_configs as $config) {
    test($config["function"], $config["method"], $config["intervals_number"]);
}
?>