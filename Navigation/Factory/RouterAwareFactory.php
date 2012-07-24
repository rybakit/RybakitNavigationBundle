<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Factory;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RouterAwareFactory implements FactoryInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $generator;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @param UrlGeneratorInterface $generator
     * @param FactoryInterface|null $factory
     */
    public function __construct(UrlGeneratorInterface $generator, FactoryInterface $factory = null)
    {
        $this->generator = $generator;
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
        if (!empty($options['route'])) {
            $route = (array) $options['route'] + array('', array(), false);
            $options['uri'] = $this->generator->generate($route[0], $route[1], $route[2]);
        }

        return $this->factory->create($options, $parent);
    }
}
