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
     * @var ItemInterface
     */
    protected $active;

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
        if (!$this->active && $this->matcher->match($options)) {
            $options['active'] = true;
            $this->active = $item;
        }
    }

    /**
     * @return ItemInterface|null
     */
    public function getActive()
    {
        return $this->active;
    }
}
