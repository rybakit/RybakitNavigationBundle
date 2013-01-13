<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

interface ItemInterface extends \IteratorAggregate
{
    /**
     * @return self|null
     */
    public function getParent();

    /**
     * @param ItemInterface $item
     *
     * @return ItemInterface
     *
     * @throws \InvalidArgumentException
     */
    public function addChild(ItemInterface $item);

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function set($name, $value);

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($name, $default = null);
}
