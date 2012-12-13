<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Iterator;

class TreeIterator extends \IteratorIterator implements \RecursiveIterator
{
    /**
     * @var int
     */
    protected $level;

    /**
     * @var int
     */
    protected $maxLevel = PHP_INT_MAX;

    public function __construct(\Traversable $iterator, $level = 0)
    {
        parent::__construct($iterator);

        $this->level = (int) $level;
    }

    public function hasChildren()
    {
        if ($this->level >= $this->maxLevel) {
            return false;
        }

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
        $iterator = new static($this->current(), $this->level + 1);
        $iterator->setMaxLevel($this->maxLevel);

        return $iterator;
    }

    /**
     * @param int $maxLevel
     */
    public function setMaxLevel($maxLevel)
    {
        $this->maxLevel = (int) $maxLevel;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
}
