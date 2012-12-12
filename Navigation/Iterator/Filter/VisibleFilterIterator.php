<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Iterator\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\Item;

class VisibleFilterIterator extends \RecursiveFilterIterator
{
    protected $isVisible;

    public function __construct(\RecursiveIterator $iterator, $isVisible = true)
    {
        parent::__construct($iterator);

        $this->isVisible = $isVisible;
    }

    public function accept()
    {
        $current = $this->current();

        return $current instanceof Item && $this->isVisible == $current->isVisible();
    }

    public function getChildren()
    {
        return new static($this->getInnerIterator()->getChildren(), $this->isVisible);
    }
}
