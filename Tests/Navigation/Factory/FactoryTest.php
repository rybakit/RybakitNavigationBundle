<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Factory;

use Rybakit\Bundle\NavigationBundle\Navigation\Factory\Factory;
use Rybakit\Bundle\NavigationBundle\Navigation\NavigationItem;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $factory = new Factory(new NavigationItem());
        $nav = $factory->create(array(
            'label'     => '0',
            'children'  => array(
                array('label' => '1.1'),
                array('label' => '1.2', 'children' => array(array('label' => '1.2.1'))),
                array('label' => '1.3'),
            ),
        ));

        $this->assertEquals($this->dumpContainer($nav), '0--1.1--1.2--1.2.1--1.3--');
    }

    protected function dumpContainer(NavigationItem $container, &$dump = '')
    {
        $dump .= $container->getLabel().'--';

        foreach ($container as $item) {
            $this->dumpContainer($item, $dump);
        }

        return $dump;
    }
}