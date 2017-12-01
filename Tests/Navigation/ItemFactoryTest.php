<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation;

use PHPUnit\Framework\TestCase;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\FilterInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\Item;
use Rybakit\Bundle\NavigationBundle\Navigation\ItemFactory;

class ItemFactoryTest extends TestCase
{
    public function testCreate()
    {
        $traversalIndex = 0;

        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->any())
            ->method('apply')
            ->will($this->returnCallback(function ($options, Item $item) use (&$traversalIndex) {
                $item->label = $options['label'];
                $item->__index = $traversalIndex++;
            }));

        $factory = new ItemFactory($filter, new Item());
        $this->assertSame($filter, $factory->getFilter());

        $root = $factory->create([
            'label' => '0',
            'children' => [
                ['label' => '1.1'],
                ['label' => '1.2', 'children' => [['label' => '1.2.1']]],
                ['label' => '1.3'],
            ],
        ]);

        // post-order traversal
        $this->assertEquals('(4:0)(0:1.1)(2:1.2)(1:1.2.1)(3:1.3)', $this->dumpItem($root));
    }

    protected function dumpItem(Item $item)
    {
        $dump = sprintf('(%d:%s)', $item->__index, $item->label);

        foreach ($item as $child) {
            $dump .= $this->dumpItem($child);
        }

        return $dump;
    }
}
