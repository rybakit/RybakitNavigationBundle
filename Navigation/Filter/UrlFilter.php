<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlFilter implements FilterInterface
{
    private $generator;

    /**
     * @param UrlGeneratorInterface $generator
     */
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
            $route = (array) $options['route'] + array('', array(), false);
            $url = $this->generator->generate($route[0], $route[1], $route[2]);
            $item->setAttribute('url', $url);

            /*
            $generator = $this->generator;
            $options['uri'] = new LazyAttribute(function(ItemInterface $item) use ($generator, $route) {
                $item->setAttribute('uri', $generator->generate($route[0], $route[1], $route[2]));
            });
            */
        }
    }
}
