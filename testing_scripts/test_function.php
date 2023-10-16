<?php
function test(string $FUNCTION, string $METHOD, int $N): void {
    //  f()    $X_0    $X_N    $Y_0
    require "functions/" . $FUNCTION . ".php";

    require "methods/" . $METHOD . ".php";

    $h = ($X_N - $X_0)/$N;

    function getNet() {
        global $X_0, $X_N, $h;
        $x = [$X_0];
        $i = 0;

        while ($x[$i] < $X_N) {
            $i += 1;
            array_push($x, $h*$i + $X_0);
        }

        return $x;
    }

    include "solution.php";
    file_put_contents("plots/" . $FUNCTION . "_solution.gpl", $output);

    include "solve.php";
    file_put_contents("plots/" . $FUNCTION . "_" . $METHOD . "_" . $N . "_solve.gpl", $output);

    include "errors.php";
    file_put_contents("plots/" . $FUNCTION . "_" . $METHOD . "_errors.gpl", $output);
}
?>