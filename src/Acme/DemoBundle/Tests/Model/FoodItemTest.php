<?php
namespace Acme\DemoBundle\Model;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FoodItemTest extends WebTestCase
{
    function testMagicMethods()
    {
        /** @var Item $item */
        $item = new FoodItem(array(
            'name' => 'bread',
            'amount' => 5,
            'unit' => FoodItem::UNIT_SLICES,
        ));

        $this->assertEquals('bread', $item->name);
        $this->assertEquals(5, $item->amount);
        $this->assertEquals(FoodItem::UNIT_SLICES, $item->unit);

        $item->amount = 10;
        $this->assertEquals(10, $item->amount);

        $this->assertEquals('bread', (string) $item);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testExceptionInvalidUnit()
    {
        new FoodItem(array(
            'name' => 'bread',
            'amount' => 5,
            'unit' => 'invalid',
        ));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testExceptionInvalidAmount()
    {
        new FoodItem(array(
            'name' => 'bread',
            'amount' => 'invalid',
            'unit' => FoodItem::UNIT_SLICES,
        ));
    }
}