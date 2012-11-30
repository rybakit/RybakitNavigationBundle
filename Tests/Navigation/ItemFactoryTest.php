<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation;

use Rybakit\Bundle\NavigationBundle\Navigation\Item;
use Rybakit\Bundle\NavigationBundle\Navigation\ItemFactory;

class ItemFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $factory = new ItemFactory(null, new Item());

        $nav = $factory->create(array(
            'label'     => '0',
            'children'  => array(
                array(
                    'label' => '1.1',
                ),
                array(
                    'label'     => '1.2',
                    'children'  => array(
                        array(
                            'label' => '1.2.1',
                        ),
                    ),
                ),
                array(
                    'label' => '1.3',
                ),
            ),
        ));

        $this->assertEquals('(0)(1.1)(1.2)(1.2.1)(1.3)', $this->dumpItem($nav));
    }

    public function testFilter()
    {
        $filter = $this->getMock('Rybakit\Bundle\NavigationBundle\Navigation\Filter\FilterInterface');
        $filter->expects($this->once())->method('apply')
            ->with($this->equalTo(array('label' => 'root')))
            ->will($this->returnValue(array('label' => '*root*')));

        $factory = new ItemFactory($filter, new Item());
        $root = $factory->create(array('label' => 'root'));

        $this->assertEquals('*root*', $root->getLabel());
    }

    protected function dumpItem(Item $container)
    {
        $dump = '('.$container->getLabel().')';

        foreach ($container as $item) {
            $dump .= $this->dumpItem($item);
        }

        return $dump;
    }
}
