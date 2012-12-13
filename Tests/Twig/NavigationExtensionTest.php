<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Twig;

use Rybakit\Bundle\NavigationBundle\Tests\Fixtures\Item;
use Rybakit\Bundle\NavigationBundle\Twig\NavigationExtension;

class NavigationExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider testGetAncestorProvider
     */
    public function testGetAncestor($item, $level, $ancestor)
    {
        $ext = new NavigationExtension('foo.bar.twig');
        $this->assertSame($ancestor, $ext->getAncestor($item, $level));
    }

    public function testGetAncestorProvider()
    {
        $root = new Item();
        $level1 = new Item();
        $level1->setParent($root);
        $level2 = new Item();
        $level2->setParent($level1);

        return array(
            array($root, 0, $root),
            array($root, 1, null),
            array($root, -1, null),

            array($level1, 0, $root),
            array($level1, -1, $root),
            array($level1, '-1', $root),
            array($level1, '-9', null),
            array($level1, 9, null),

            array($level2, 1, $level1),
            array($level2, '2', $level2),
            array($level2, '0', $root),
            array($level2, 'foo', null),
        );
    }
}
