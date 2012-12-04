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
     * @var FilterInterface
     */
    protected $parent;

    /**
     * @var bool
     */
    protected $isMatched = false;

    /**
     * @param MatcherInterface     $matcher
     * @param FilterInterface|null $parent
     */
    public function __construct(MatcherInterface $matcher, FilterInterface $parent = null)
    {
        $this->matcher = $matcher;
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(array $options, ItemInterface $item)
    {
        if (!$this->isMatched && $this->matcher->match($options)) {
            $options = $this->doApply($options);
            $this->isMatched = true;
        }
    }
}
