<?php
class DataList implements ArrayAccess, Countable, IteratorAggregate {
    private $head = null;
    private $tail = null;
    private $size = 0;

    public function __construct(array $data) {
        foreach ($data as $value) {
            $this[] = $value;
        }
    }
    public function offsetExists($offset): bool {
        if (gettype($offset) !== "integer")
            throw new InvalidArgumentException("List offset should be integer");

        return $offset >= 0 && $offset < count($this);
    }
    public function offsetGet($offset): mixed {
        if (!$this->offsetExists($offset))
            throw new OutOfBoundsException("List offset should be equals or greater than 0 and lesser than list size");

        $current = $this->head;
        for ($i=0; $i < $offset; $i++) { 
            $current = $current->next;
        }
        return $current->data;
    }
    public function offsetSet($offset, mixed $value): void {
        if (is_null($offset)) {
            $current = new class {
                public $data;
                public $next = null;
            };
            $current->data = $value;
            if ($this->tail == null) {
                $this->head = $this->tail = $current;
                $this->size = 1;
            } else {
                $this->tail->next = $current;
                $this->tail = $this->tail->next;
                $this->size += 1;
            }
        } else if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException("List offset should be equals or greater than 0 and lesser than list size");
        } else {
            $current = $this->head;
            for ($i=0; $i < $offset; $i++) { 
                $current = $current->next;
            }
            $current->data = $value;
        }
    }
    public function offsetUnset($offset): void {
        if (!$this->offsetExists($offset))
            throw new OutOfBoundsException("List offset should be equals or greater than 0 and lesser than list size");

        $this->size -= 1;
        if ($this->size === 0) {
            $this->head = null;
            $this->tail = null;
            return;
        }

        $current = $this->head;
        if ($offset === 0) {
            $this->head = $current->next;
            $current->next = null;
            return;
        }

        for ($i=1; $i < $offset; $i++) { 
            $current = $current->next;
        }
        if ($offset === $this->size) {
            $this->tail = $current;
        }
        $temp = $current->next;
        $current->next = $temp->next;
        $temp->next = null;
    }
    public function count(): int {
        return $this->size;
    }
    public function getIterator(): Traversable {
        return (function() {
            $current = $this->head;
            for ($i=0; $current != null ; $i++) { 
                yield $i => $current->data;
                $current = $current->next;
            }
        })();
    }
}
?>