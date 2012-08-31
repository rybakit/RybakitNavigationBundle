<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\Matcher\MatcherInterface;

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
    public function filter(array $options)
    {
        if (!$this->isMatched && $this->matcher->match($options)) {
            $options['active'] = true;
            $this->isMatched = true;
        }

        return $this->parent ? $this->parent->filter($options) : $options;
    }
}
