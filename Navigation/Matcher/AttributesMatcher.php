<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Matcher;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class AttributesMatcher implements MatcherInterface
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function match(ItemInterface $item)
    {
        foreach ($this->attributes as $name => $value) {
            if ($item->getAttribute($name) !== $value) {
                return false;
            }
        }

        return true;
    }
}
