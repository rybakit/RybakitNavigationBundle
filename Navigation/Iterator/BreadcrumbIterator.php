<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Iterator;

use Rybakit\Bundle\NavigationBundle\Navigation\ContainerInterface;

class BreadcrumbIterator implements \Iterator
{
    protected $last;
    protected $current;
    protected $pos = 0;

    public function __construct(ContainerInterface $last)
    {
        $this->last = $last;
        $this->current = $last;
    }

    public function rewind()
    {
        $this->current = $this->last;
        $this->pos = 0;
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->pos;
    }

    public function next()
    {
        if ($this->current) {
            $this->current = $this->current->getParent();
        }

        ++$this->pos;
    }

    public function valid()
    {
        return null !== $this->current;
    }
}
