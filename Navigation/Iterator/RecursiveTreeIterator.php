<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Iterator;

class RecursiveTreeIterator extends \IteratorIterator implements \RecursiveIterator
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
        if ($this->level < $this->maxLevel) {
            $current = $this->current();

            if ($current instanceof \IteratorAggregate) {
                $current = $current->getIterator();
            }

            if ($current instanceof \Iterator) {
                return 0 !== iterator_count($current);
            }
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
