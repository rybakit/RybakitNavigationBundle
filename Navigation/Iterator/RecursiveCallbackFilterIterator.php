<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Iterator;

class RecursiveCallbackFilterIterator extends \RecursiveFilterIterator
{
    /**
     * @var \Closure|string|array A PHP callback
     */
    protected $callback;

    /**
     * @param \RecursiveIterator    $iterator
     * @param \Closure|string|array $callback
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(\RecursiveIterator $iterator, $callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('The given callback is not a valid callable.');
        }

        $this->callback = $callback;

        parent::__construct($iterator);
    }

    public function accept()
    {
        return $this->filter($this->getInnerIterator());
    }

    public function hasChildren()
    {
        $iterator = $this->current();

        if ($iterator instanceof \IteratorAggregate) {
            $iterator = $iterator->getIterator();
        }

        foreach ($iterator as $item) {
            if ($this->filter($iterator)) {
                return true;
            }
        }

        return false;
    }

    public function getChildren()
    {
        $iterator = $this->current();

        if ($iterator instanceof \IteratorAggregate) {
            $iterator = $iterator->getIterator();
        }

        return new static(new RecursiveItemIterator($iterator), $this->callback);
    }

    protected function filter(\Iterator $iterator)
    {
        return call_user_func($this->callback, $iterator->current(), $iterator->key(), $iterator);
    }
}
