<?php
class Vector implements ArrayAccess {
    public $container = [0.0, 0.0, 0.0];
    public function __construct(float $v1, float $v2, float $v3) {
        $this->container[0] = $v1;
        $this->container[1] = $v2;
        $this->container[2] = $v3;
    }
    public function __add(Vector $vector) {
        $result = clone $this;
        $result->container[0] += $vector->container[0];
        $result->container[1] += $vector->container[1];
        $result->container[2] += $vector->container[2];
        return $result;
    }
    public function __sub(Vector $vector) {
        $result = clone $this;
        $result->container[0] -= $vector->container[0];
        $result->container[1] -= $vector->container[1];
        $result->container[2] -= $vector->container[2];
        return $result;
    }
    public function __mul(float $value) {
        $result = clone $this;
        $result->container[0] *= $value;
        $result->container[1] *= $value;
        $result->container[2] *= $value;
        return $result;
    }
    public function __div(float $value) {
        $result = clone $this;
        $result->container[0] /= $value;
        $result->container[1] /= $value;
        $result->container[2] /= $value;
        return $result;
    }
    public function offsetExists($offset): bool {
        return isset($this->container[$offset]);
    }
    public function offsetGet($offset): mixed {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
    public function offsetSet($offset, $value): void {
        if (!is_null($offset) && $offset >= 0 && $offset < 3) {
            $this->container[$offset] = $value;
        }
    }
    public function offsetUnset($offset): void {}
}

function norm(Vector|float|int $value): float {
    if ($value instanceof Vector) {
        return sqrt(
            $value->container[0]*$value->container[0] +
            $value->container[1]*$value->container[1] +
            $value->container[2]*$value->container[2]
        );
    } else {
        return abs($value);
    }
}

$METHOD = "runge-kutta5";
$N = 512;
/* возможные:
    "solution",
    "solve",
    "errors", */
$scripts = [
    "errors",
];

$X_0 = 0.0;
$X_N = 300.0;
$Y_0 = new Vector(4.0, 1.1, 4.0);

function f(float $x, Vector $y): Vector {
    $k1 = 77.27;
    $k2 = 8.375*(10**(-6));
    $k3 = 1/77.27;
    $k4 = 0.161;

    return new Vector(
        $k1*($y[1] - $y[0]*$y[1] + $y[0] - $k2*($y[0]*$y[0])),
        $k3*(-$y[1] - $y[0]*$y[1] + $y[2]),
        $k4*($y[0] - $y[2])
    );
}

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

if (in_array("solve", $scripts)) {
    $output1 = "";
    $output2 = "";
    $output3 = "";

    $x = getNet();
    $y = solve($x);

    for ($i=0; $i < count($x); $i++) { 
        $output1 .= $x[$i] . " " . $y[$i][0] . "\n";
        $output2 .= $x[$i] . " " . $y[$i][1] . "\n";
        $output3 .= $x[$i] . " " . $y[$i][2] . "\n";
    }
    file_put_contents("model/" . $METHOD . "_" . $N . "_solve1.gpl", $output1);
    file_put_contents("model/" . $METHOD . "_" . $N . "_solve2.gpl", $output2);
    file_put_contents("model/" . $METHOD . "_" . $N . "_solve3.gpl", $output3);
}

if (in_array("errors", $scripts)) {
    $output = "";
    $y_buff = [];
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
    $h /= 2;

    while ($h > $EPSILON && $errors_num < 20) {
        $y_buff = $y;
        $x = getNet();
        $y = solve($x);
        $error = norm($y[0] - $y_buff[0])/(2**$theory_orders[$METHOD] - 1);
        for ($i=1; $i < count($y_buff); $i++) { 
            $i_error = norm($y[2*$i] - $y_buff[$i])/(2**$theory_orders[$METHOD] - 1);
            if ($error < $i_error) {
                $error = $i_error;
            }
        }
        $output = $output . count($y_buff)-1 . " " . $error . "\n";

        $h /= 2;
    }

    $h = ($X_N - $X_0)/$N;

    file_put_contents("model/" . $METHOD . "_errors.gpl", $output);
}
?>