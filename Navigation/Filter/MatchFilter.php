<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\Matcher\MatcherInterface;

class MatchFilter implements FilterInterface
{
    /**
     * @var MatcherInterface
     */
    protected $matcher;

    /**
     * @var ItemInterface
     */
    protected $matched;

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
        if (!$this->matched && $this->matcher->match($item)) {
            $this->matched = $item;
        }
    }

    /**
     * @return ItemInterface|null
     */
    public function getMatched()
    {
        return $this->matched;
    }
}
