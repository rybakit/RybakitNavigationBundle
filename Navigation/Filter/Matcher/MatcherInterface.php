<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter\Matcher;

interface MatcherInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function match($value);
}
