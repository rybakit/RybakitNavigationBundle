<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\Matcher\MatcherInterface;

abstract class AbstractMatchingFilter implements FilterInterface
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
    public function apply(array $options)
    {
        if (!$this->isMatched && $this->matcher->match($options)) {
            $options = $this->doApply($options);
            $this->isMatched = true;
        }

        return $this->parent ? $this->parent->apply($options) : $options;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    abstract protected function doApply(array $options);
}
