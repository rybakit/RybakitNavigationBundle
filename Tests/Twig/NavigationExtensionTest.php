<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Rybakit\Bundle\NavigationBundle\Tests\Fixtures\Item;
use Rybakit\Bundle\NavigationBundle\Twig\NavigationExtension;

class NavigationExtensionTest extends TestCase
{
    /**
     * @dataProvider provideGetAncestorData
     */
    public function testGetAncestor(Item $item, $level, Item $ancestor = null)
    {
        $ext = new NavigationExtension('foo.bar.twig');
        $this->assertSame($ancestor, $ext->getAncestor($item, $level));
    }

    public function provideGetAncestorData()
    {
        $root = new Item();
        $level1 = new Item();
        $level1->setParent($root);
        $level2 = new Item();
        $level2->setParent($level1);

        return [
            [$root, 0, $root],
            [$root, 1, null],
            [$root, -1, null],

            [$level1, 0, $root],
            [$level1, -1, $root],
            [$level1, '-1', $root],
            [$level1, '-9', null],
            [$level1, 9, null],

            [$level2, 1, $level1],
            [$level2, '2', $level2],
            [$level2, '0', $root],
            [$level2, 'foo', null],
        ];
    }
}
