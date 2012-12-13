<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Iterator;

class CustomFilterIterator extends \RecursiveFilterIterator
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
        return new static($this->getInnerIterator()->getChildren(), $this->func);
    }
}
