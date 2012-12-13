<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation;

use Rybakit\Bundle\NavigationBundle\Navigation\Item;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function testClone()
    {
        $item0   = new Item('0');
        $item11  = new Item('1.1');
        $item12  = new Item('1.2');
        $item121 = new Item('1.2.1');

        $item0->add($item11);
        $item0->add($item12);
        $item12->add($item121);

        $clone0 = clone $item0;
        $iterator = $clone0->getIterator();
        $iterator->rewind();
        $clone11 = $iterator->current();
        $iterator->next();
        $clone12 = $iterator->current();
        $iterator = $clone12->getIterator();
        $iterator->rewind();
        $clone121 = $iterator->current();

        $this->assertItemCopy($item0, $clone0);
        $this->assertItemCopy($item11, $clone11, $clone0);
        $this->assertItemCopy($item12, $clone12, $clone0);
        $this->assertItemCopy($item121, $clone121, $clone12);
    }

    public function testSetVisible()
    {
        $item = new Item();
        $this->assertTrue($item->isVisible());

        $parent = new Item();
        $parent->add($item);
        $parent->setVisible(false);

        $this->assertFalse($item->isVisible());
    }

    protected function assertItemCopy(Item $original, Item $clone, Item $cloneParent = null)
    {
        $this->assertNotSame($original, $clone);
        $this->assertEquals($original->label, $clone->label);
        $this->assertSame($cloneParent, $clone->getParent());

        if ($originalParent = $original->getParent()) {
            $this->assertNotSame($originalParent, $clone->getParent());
        }
    }
}
