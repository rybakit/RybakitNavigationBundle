<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\UrlFilter;

class UrlFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider testApplyProvider
     */
    public function testApply(array $route)
    {
        $item = $this->getMock('\\Rybakit\\Bundle\\NavigationBundle\\Navigation\\ItemInterface');

        $urlGenerator = $this->getMock('\\Symfony\Component\\Routing\\Generator\\UrlGeneratorInterface');
        $urlGenerator->expects($this->once())->method('generate')
            ->with($this->equalTo($route[0]), $this->equalTo($route[1]), $this->equalTo($route[2]))
            ->will($this->returnValue('generated_url'));

        $options = array('route' => $route);
        $filter = new UrlFilter($urlGenerator);
        $filter->apply($options, $item);

        $this->assertEquals(array('route' => $route, 'uri' => 'generated_url'), $options);
    }

    public function testApplyProvider()
    {
        return array(
            array(array('my_route1', array(), false)),
            array(array('my_route2', array(), true)),
            array(array('my_route3', array('param' => 'value'), false)),
            array(array('my_route4', array('param' => 'value'), true)),
        );
    }
}
