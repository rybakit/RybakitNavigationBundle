<?php

namespace Rybakit\Bundle\NavigationBundle\Twig;

use Rybakit\Bundle\NavigationBundle\Navigation\ContainerInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\NavigationItem;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\RecursiveContainerIterator;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\RecursiveCallbackFilterIterator;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\BreadcrumbIterator;

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
            'nav_menu'        => new \Twig_Function_Method($this, 'renderMenu', array('is_safe' => array('html'))),
            'nav_breadcrumbs' => new \Twig_Function_Method($this, 'renderBreadcrumbs', array('is_safe' => array('html'))),
        );
    }

    /**
     * Renders a menu.
     *
     * @param ContainerInterface $navTree
     * @param array              $options
     *
     * @return string
     */
    public function renderMenu(ContainerInterface $navTree, array $options = array())
    {
        $this->ensureTemplate();

        $iterator = new RecursiveContainerIterator($navTree);

        if (!empty($options['visible_only']) && $options['visible_only']) {
            $iterator = new RecursiveCallbackFilterIterator($iterator, function($current) {
                return !$current instanceof NavigationItem || $current->isVisible();
            });
        }

        $renderer = $this->createMenuRenderer($iterator, $this->template, $options);

        return $this->template->renderBlock('menu', array(
            'items'   => $renderer->render(),
            'options' => $options,
        ));
    }

    /**
     * Renders a breadcrumbs.
     *
     * @param ContainerInterface $current
     * @param array              $options
     *
     * @return string
     */
    public function renderBreadcrumbs(ContainerInterface $current, array $options = array())
    {
        $this->ensureTemplate();

        $items = $this->createBreadcrumbIterator($current);

        return $this->template->renderBlock('breadcrumbs', array(
            'items'   => $items,
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

    protected function ensureTemplate()
    {
        if (!$this->template instanceof \Twig_Template) {
            $this->template = $this->environment->loadTemplate($this->template);
        }
    }

    protected function createMenuRenderer(\Traversable $iterator, \Twig_Template $template, array $options = array())
    {
        return new MenuRenderer($iterator, $template, $options);
    }

    protected function createBreadcrumbIterator(ContainerInterface $current)
    {
        return new BreadcrumbIterator($current);
    }
}
