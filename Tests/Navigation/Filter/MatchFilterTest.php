<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Filter;

use PHPUnit\Framework\TestCase;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\Matcher\MatcherInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\MatchFilter;
use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class MatchFilterTest extends TestCase
{
    /**
     * @dataProvider provideApplyData
     */
    public function testApply(bool $isMatched)
    {
        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->expects($this->once())->method('match')->will($this->returnValue($isMatched));

        $options = [];
        $item = $this->createMock(ItemInterface::class);

        $filter = new MatchFilter($matcher);
        $filter->apply($options, $item);

        $isMatched ? $this->assertEquals($item, $filter->getMatched()) : $this->assertNull($filter->getMatched());
    }

    public function provideApplyData()
    {
        return [[true], [false]];
    }
}
