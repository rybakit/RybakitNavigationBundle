<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Matcher;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

interface MatcherInterface
{
    /**
     * @param ItemInterface $item
     *
     * @return bool
     */
    public function match(ItemInterface $item);
}
