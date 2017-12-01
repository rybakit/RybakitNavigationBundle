<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlFilter implements FilterInterface
{
    private $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(array &$options, ItemInterface $item)
    {
        if (!empty($options['route'])) {
            $route = (array)$options['route'] + ['', [], UrlGeneratorInterface::ABSOLUTE_PATH];
            $options['uri'] = $this->generator->generate($route[0], $route[1], $route[2]);
        }
    }
}
