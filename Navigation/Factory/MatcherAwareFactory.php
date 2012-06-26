<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Factory;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\Matcher\MatcherInterface;

class MatcherAwareFactory implements FactoryInterface
{
    /**
     * @var MatcherInterface
     */
    protected $matcher;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var bool
     */
    protected $isMatched = false;

    /**
     * @param MatcherInterface      $matcher
     * @param FactoryInterface|null $factory
     */
    public function __construct(MatcherInterface $matcher, FactoryInterface $factory = null)
    {
        $this->matcher = $matcher;
        $this->factory = $factory ?: new Factory();
        $this->factory->setParent($this);
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(FactoryInterface $parent)
    {
        $this->factory->setParent($parent);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = array(), ItemInterface $parent = null)
    {
        if (!$this->isMatched && $this->matcher->match($options)) {
            //$item->setActive();
            $options['active'] = true;
            $this->isMatched = true;
        }

        return $this->factory->create($options, $parent);
    }
}
