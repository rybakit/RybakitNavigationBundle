<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

interface ItemInterface extends \IteratorAggregate
{
    /**
     * @param ItemInterface|null $parent
     *
     * @return ItemInterface
     *
     * @throws \InvalidArgumentException
     */
    public function setParent(ItemInterface $parent = null);

    /**
     * @return ItemInterface|null
     */
    public function getParent();

    /**
     * @param ItemInterface $item
     *
     * @return ItemInterface
     *
     * @throws \InvalidArgumentException
     */
    public function add(ItemInterface $item);

    /**
     * @param ItemInterface $item
     *
     * @return ItemInterface
     */
    public function remove(ItemInterface $item);

    /**
     * @param ItemInterface $item
     *
     * @return bool
     */
    public function has(ItemInterface $item);

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setAttribute($name, $value);

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null);
}
