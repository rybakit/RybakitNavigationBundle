<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Attribute;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

interface AttributeSetterInterface
{
    /**
     * @param ItemInterface $item
     * @param string        $attrName
     */
    public function set(ItemInterface $item, $attrName);
}
