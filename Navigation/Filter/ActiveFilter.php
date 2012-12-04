<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\Matcher\MatcherInterface;

class ActiveFilter implements FilterInterface
{
    /**
     * @var MatcherInterface
     */
    protected $matcher;

    /**
     * @var bool
     */
    protected $isMatched = false;

    /**
     * @param MatcherInterface $matcher
     */
    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(array &$options, ItemInterface $item)
    {
        if (!$this->isMatched && $this->matcher->match($options)) {
            $options['active'] = true;
            $this->isMatched = true;
        }
    }
}
