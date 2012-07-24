<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Matcher;

interface MatcherInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function match($value);
}
