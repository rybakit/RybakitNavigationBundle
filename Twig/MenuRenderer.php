<?php

namespace Rybakit\Bundle\NavigationBundle\Twig;

class MenuRenderer extends \RecursiveIteratorIterator
{
    const BLOCK_MENU_ITEM = 'menu_item';
    const BLOCK_MENU_ITEM_CHILDREN = 'menu_item_children';

    /**
     * @var \Twig_Template
     */
    protected $template;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var \SplStack
     */
    protected $elementStack;

    /**
     * @var bool
     */
    protected $isCurrentHasChildren = false;

    /**
     * @param \Traversable   $iterator
     * @param \Twig_Template $template
     * @param array          $options
     */
    public function __construct(\Traversable $iterator, \Twig_Template $template, array $options = array())
    {
        parent::__construct($iterator, \RecursiveIteratorIterator::SELF_FIRST);

        if (!empty($options['depth'])) {
            $this->setMaxDepth($options['depth']);
        }

        $this->template = $template;
        $this->options = $options;
        $this->elementStack = new \SplStack();
    }

    public function current()
    {
        $current = parent::current();

        if ($this->isCurrentHasChildren && (false === $this->getMaxDepth() || $this->getDepth() < $this->getMaxDepth())) {
            $this->elementStack->push($current);
        } else {
            $this->template->displayBlock(static::BLOCK_MENU_ITEM, array(
                'item'    => $current,
                'options' => $this->options,
            ));
        }

        return $current;
    }

    public function callHasChildren()
    {
        $this->isCurrentHasChildren = parent::callHasChildren();

        return $this->isCurrentHasChildren;
    }

    public function beginChildren()
    {
        ob_start();
    }

    public function endChildren()
    {
        $this->template->displayBlock(static::BLOCK_MENU_ITEM_CHILDREN, array(
            'item'      => $this->elementStack->pop(),
            'items'     => ob_get_clean(),
            'options'   => $this->options,
        ));
    }

    public function render()
    {
        ob_start();
        foreach ($this as $item);

        return ob_get_clean();

        /*
        $level = ob_get_level();
        ob_start();

        try {
            foreach ($this as $item);
            $result = ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw $e;
        }

        return $result;
        */
    }
}
