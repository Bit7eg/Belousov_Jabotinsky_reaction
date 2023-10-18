<?php
$func_list = [
    "test-1",
    "test-2",
    "test-3",
    "test-4",
    "test-5",
    "test-6",
];
$methods_list = [
    "euler",
    "geer4",
    "predictor-adams",
    "predictor-iterations",
    "predictor-only",
    "runge-kutta3",
    "runge-kutta4",
    "runge-kutta5",
];

require "test_function.php";

foreach ($func_list as $function) {
    test($function, "euler", 1, ["solution"]);
    echo "check1";
    foreach ($methods_list as $method) {
        for ($i=64; $i < 2**20; $i*=2) { 
            test($function, $method, $i, ["solve"]);
        }
        echo "check2";
        test($function, $method, 1, ["errors"]);
        echo "check3";
    }
    echo "check4";
}
?>