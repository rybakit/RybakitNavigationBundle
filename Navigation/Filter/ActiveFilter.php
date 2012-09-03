<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\Matcher\MatcherInterface;

class ActiveFilter extends AbstractMatchingFilter
{
    /**
     * {@inheritdoc}
     */
    protected function doApply(array $options)
    {
        $options['active'] = true;

        return $options;
    }
}
