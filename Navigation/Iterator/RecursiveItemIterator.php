<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Iterator;

class RecursiveItemIterator extends \IteratorIterator implements \RecursiveIterator
{
    public function hasChildren()
    {
        $iterator = $this->current();

        if ($iterator instanceof \IteratorAggregate) {
            $iterator = $iterator->getIterator();
        }

        if ($iterator instanceof \Iterator) {
            return 0 !== iterator_count($iterator);
        }

        return false;
    }

    public function getChildren()
    {
        return new static($this->current());
    }
}
