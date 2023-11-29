<?php
class Vector implements ArrayAccess, Countable, IteratorAggregate {
    private $container = [];

    public function __construct(array $val) {
        foreach ($val as $v) {
            if (!is_numeric($v)) {
                throw new InvalidArgumentException("Vector should contain only numeric values");
            }
        }
        $this->container = $val;
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
    public function count(): int {
        return count($this->container);
    }
    public function getIterator(): ArrayIterator {
        return new ArrayIterator($this->container);
    }
    public function __toString(): string {
        return implode(" ", $this->container);
    }

    public function add(Vector $vector): Vector {
        $result = clone $this;
        foreach ($vector as $key => $value) {
            $result->container[$key] += $value;
        }
        return $result;
    }
    public function sub(Vector $vector): Vector {
        $result = clone $this;
        foreach ($vector as $key => $value) {
            $result->container[$key] -= $value;
        }
        return $result;
    }
    public function mul(float $value): Vector {
        $result = clone $this;
        foreach ($result as $key => $v) {
            $result->container[$key] *= $value;
        }
        return $result;
    }
    public function div(float $value): Vector {
        $result = clone $this;
        foreach ($result as $key => $v) {
            $result->container[$key] /= $value;
        }
        return $result;
    }
}

function norm(Vector|float|int $value): float {
    if ($value instanceof Vector) {
        $sum = 0.0;
        for ($i=0; $i < count($value); $i++) { 
            $sum += $value[$i] * $value[$i];
        }
        return sqrt($sum);
    } else {
        return abs($value);
    }
}
?>