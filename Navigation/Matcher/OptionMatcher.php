<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Matcher;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class OptionMatcher implements MatcherInterface
{
    /**
     * @var string An option name
     */
    protected $name;

    /**
     * @var mixed An option value
     */
    protected $value;

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __construct($name, $value)
    {
        $this->name  = (string) $name;
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function match(ItemInterface $item)
    {
        return $item->get($this->name) === $this->value;
    }
}
