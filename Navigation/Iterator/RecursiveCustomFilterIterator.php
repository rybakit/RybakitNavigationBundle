<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Iterator;

class RecursiveCustomFilterIterator extends \RecursiveFilterIterator
{
    /**
     * @var \Closure
     */
    protected $func;

    public function __construct(\RecursiveIterator $iterator, \Closure $func)
    {
        parent::__construct($iterator);

        $this->func = $func;
    }

    public function accept()
    {
        return call_user_func($this->func, $this->current());
    }

    public function getChildren()
    {
        $iterator = $this->getInnerIterator();
        if ($iterator->hasChildren()) {
            return new static($iterator->getChildren(), $this->func);
        }

        return null;
    }

    public function hasChildren()
    {
        if ($iterator = $this->getChildren()) {
            $iterator->rewind();

            return $iterator->valid();
        }

        return false;
    }
}
