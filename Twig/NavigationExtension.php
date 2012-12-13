<?php

namespace Rybakit\Bundle\NavigationBundle\Twig;

use Rybakit\Bundle\NavigationBundle\Navigation\Item;
use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\BreadcrumbIterator;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\CustomFilterIterator;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\TreeIterator;

class NavigationExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var \Twig_Template
     */
    protected $template;

    /**
     * @param \Twig_Template|string $template
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'nav' => new \Twig_Function_Method($this, 'render', array('is_safe' => array('html'))),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'tree'        => new \Twig_Filter_Method($this, 'createFilterTree'),
            'depth'       => new \Twig_Filter_Method($this, 'createFilterDepth'),
            'visible'     => new \Twig_Filter_Method($this, 'createFilterVisible'),
            'ancestor'    => new \Twig_Filter_Method($this, 'createFilterAncestor'),
            'breadcrumbs' => new \Twig_Filter_Method($this, 'createFilterBreadcrumbs'),
        );
    }

    /**
     * @param \Traversable $iterator
     *
     * @return TreeIterator
     */
    public function createFilterTree(\Traversable $iterator)
    {
        return new TreeIterator($iterator);
    }

    /**
     * TODO implement createFilterDepth()
     *
     * @param \RecursiveIterator $iterator
     * @param int                $depth
     *
     * @return CustomFilterIterator
     */
    public function createFilterDepth(\RecursiveIterator $iterator, $depth = 1)
    {
        return $iterator;
    }

    /**
     * @param \RecursiveIterator $iterator
     * @param bool               $isVisible
     *
     * @return CustomFilterIterator
     */
    public function createFilterVisible(\RecursiveIterator $iterator, $isVisible = true)
    {
        return new CustomFilterIterator($iterator, function($item) use ($isVisible) {
            return $item instanceof Item && $isVisible == $item->isVisible();
        });
    }

    /**
     * @param ItemInterface $item
     * @param int           $level
     *
     * @return ItemInterface|null
     */
    public function createFilterAncestor(ItemInterface $item, $level)
    {
        $breadcrumbs = iterator_to_array(new BreadcrumbIterator($item));
        $breadcrumbs = array_reverse($breadcrumbs);

        return isset($breadcrumbs[$level]) ? $breadcrumbs[$level] : null;
    }

    /**
     * @param ItemInterface $item
     *
     * @return BreadcrumbIterator
     */
    public function createFilterBreadcrumbs(ItemInterface $item)
    {
        return new BreadcrumbIterator($item);
    }

    /**
     * Renders a navigation.
     *
     * @see https://github.com/fabpot/Twig/pull/926
     *
     * @param mixed  $item
     * @param string $block
     * @param array  $options
     *
     * @return string
     */
    public function render($item, $block, array $options = array())
    {
        return $this->getTemplate()->renderBlock($block, array(
            'items'   => $item,
            'options' => $options,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rybakit_navigation';
    }

    /**
     * @return \Twig_Template
     */
    protected function getTemplate()
    {
        if (!$this->template instanceof \Twig_Template) {
            $this->template = $this->environment->loadTemplate($this->template);
        }

        return $this->template;
    }
}
