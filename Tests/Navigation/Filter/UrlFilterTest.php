<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Filter;

use PHPUnit\Framework\TestCase;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\UrlFilter;
use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlFilterTest extends TestCase
{
    /**
     * @dataProvider provideApplyData
     */
    public function testApply(array $route)
    {
        $item = $this->createMock(ItemInterface::class);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->expects($this->once())->method('generate')
            ->with($this->equalTo($route[0]), $this->equalTo($route[1]), $this->equalTo($route[2]))
            ->will($this->returnValue('generated_url'));

        $options = ['route' => $route];
        $filter = new UrlFilter($urlGenerator);
        $filter->apply($options, $item);

        $this->assertEquals(['route' => $route, 'uri' => 'generated_url'], $options);
    }

    public function provideApplyData()
    {
        return [
            [['my_route1', [], false]],
            [['my_route2', [], true]],
            [['my_route3', ['param' => 'value'], false]],
            [['my_route4', ['param' => 'value'], true]],
        ];
    }
}
