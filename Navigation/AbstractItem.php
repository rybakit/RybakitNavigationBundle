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
    protected $children;

    protected $data = array();

    public function __construct()
    {
        $this->children = new \SplObjectStorage();
    }

    /**
     * @param self|null $parent
     *
     * @return AbstractItem
     *
     * @throws \InvalidArgumentException
     */
    public function setParent(self $parent = null)
    {
        if ($parent === $this) {
            throw new \InvalidArgumentException('An item cannot have itself as a parent.');
        }

        if ($parent === $this->parent) {
            return $this;
        }

        if ($this->parent) {
            $this->parent->removeChild($this);
        }

        $this->parent = $parent;

        if ($parent && !$parent->hasChild($this)) {
            $this->parent->addChild($this);
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
     * {@inheritdoc}
     */
    public function addChild(ItemInterface $item)
    {
        if (!$item instanceof self) {
            throw new \InvalidArgumentException(sprintf('An item must be an instance of "%s".', __CLASS__));
        }

        if ($item === $this) {
            throw new \InvalidArgumentException('An item cannot have itself as a child.');
        }

        $this->children->attach($item);
        $item->setParent($this);

        return $this;
    }

    /**
     * @param self $item
     *
     * @return self
     */
    public function removeChild(self $item)
    {
        if ($this->hasChild($item)) {
            $this->children->detach($item);
            $item->setParent(null);
        }

        return $this;
    }

    /**
     * @param self $item
     *
     * @return bool
     */
    public function hasChild(self $item)
    {
        return $this->children->contains($item);
    }

    /**
     * Implements IteratorAggregate interface.
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        return $this->children;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return $default;
    }

    /**
     * Implements Countable interface.
     *
     * @return int
     */
    public function count()
    {
        return $this->children->count();
    }

    public function __clone()
    {
        $this->parent = null;

        $children = $this->children;
        $this->children = new \SplObjectStorage();

        foreach ($children as $item) {
            $this->addChild(clone $item);
        }
    }
}
