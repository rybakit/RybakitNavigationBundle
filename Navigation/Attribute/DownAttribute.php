<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Attribute;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class DownAttribute extends AbstractAttributeSetter
{
    /**
     * {@inheritdoc}
     */
    public function set(ItemInterface $item, $attrName)
    {
        $item->setAttribute($attrName, $this->value);

        foreach ($item->getChildren() as $child) {
            $this->set($child, $attrName);
        }
    }
}
