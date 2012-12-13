<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Fixtures;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class Item implements ItemInterface
{
    public $label;
    public $parent;
    public $children;

    public $publicProperty;
    public $nonPublicPropertyWithSetterValue;
    public $nonPublicPropertyWithSetterDefaultArgValue;
    public $isNonPublicPropertyWithSetterManyArgsCalled = false;

    public function setNonPublicPropertyWithSetter($value)
    {
        $this->nonPublicPropertyWithSetterValue = $value;
    }

    public function setNonPublicPropertyWithSetterDefaultArg($value = 'default_value')
    {
        $this->nonPublicPropertyWithSetterDefaultArgValue = $value;
    }

    public function setNonPublicPropertyWithSetterManyArgs($value1, $value2)
    {
        $this->isNonPublicPropertyWithSetterManyArgsCalled = true;
    }

    public function setParent(ItemInterface $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function add(ItemInterface $item)
    {
    }

    public function remove(ItemInterface $item)
    {
    }

    public function has(ItemInterface $item)
    {
    }

    public function getIterator()
    {
    }
}
