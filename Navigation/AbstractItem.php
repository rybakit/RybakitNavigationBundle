<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

abstract class AbstractItem implements ItemInterface, \Countable
{
    /**
     * @var ItemInterface
     */
    protected $parent;

    /**
     * @var \SplObjectStorage
     */
    protected $items;

    public function __construct()
    {
        $this->items = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(ItemInterface $parent = null)
    {
        if ($parent === $this) {
            throw new \InvalidArgumentException('An item cannot have itself as a parent.');
        }

        if ($parent === $this->parent) {
            return $this;
        }

        if ($this->parent) {
            $this->parent->remove($this);
        }

        $this->parent = $parent;

        if ($parent && !$parent->has($this)) {
            $this->parent->add($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->parent ? $this->parent->getLevel() + 1 : 0;
    }

    /**
     * {@inheritdoc}
     */
    public function add(ItemInterface $item)
    {
        if ($item === $this) {
            throw new \InvalidArgumentException('An item cannot have itself as a child.');
        }

        $this->items->attach($item);
        $item->setParent($this);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ItemInterface $item)
    {
        if ($this->has($item)) {
            $this->items->detach($item);
            $item->setParent(null);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has(ItemInterface $item)
    {
        return $this->items->contains($item);
    }

    /**
     * Implements IteratorAggregate interface.
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        return clone $this->items;
    }

    /**
     * Implements Countable interface.
     *
     * @return int
     */
    public function count()
    {
        return iterator_count($this->getIterator());
    }

    public function __clone()
    {
        $this->parent = null;

        $items = $this->items;
        $this->items = new \SplObjectStorage();

        foreach ($items as $item) {
            $this->add(clone $item);
        }
    }
}
