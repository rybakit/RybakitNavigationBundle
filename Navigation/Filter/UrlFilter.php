<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlFilter implements FilterInterface
{
    /**
     * @var FilterInterface
     */
    protected $parent;

    /**
     * @param UrlGeneratorInterface $generator
     * @param FilterInterface|null  $parent
     */
    public function __construct(UrlGeneratorInterface $generator, FilterInterface $parent = null)
    {
        $this->generator = $generator;
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(array $options)
    {
        if (!empty($options['route'])) {
            $route = (array) $options['route'] + array('', array(), false);
            $options['uri'] = $this->generator->generate($route[0], $route[1], $route[2]);
        }

        return $this->parent ? $this->parent->apply($options) : $options;
    }
}
