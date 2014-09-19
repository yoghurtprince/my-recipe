<?php
namespace Acme\DemoBundle\Model;


class FoodItemsTest extends \PHPUnit_Framework_TestCase
{
    public function countableDataProvider()
    {
        return array(
            array(
                array(
                    array(
                        'name' => 'vegemite',
                        'amount' => 5,
                        'unit' => FoodItem::UNIT_GRAMS,
                    ),
                ),
                1
            ),
            array(
                array(
                    new FoodItem(array(
                        'name' => 'bread',
                        'amount' => 5,
                        'unit' => FoodItem::UNIT_SLICES,
                    )),
                    new FoodItem(array(
                        'name' => 'cheese',
                        'amount' => 2,
                        'unit' => FoodItem::UNIT_SLICES,
                    )),
                ),
                2
            ),
        );
    }

    /**
     * @param array|Item[] $itemsData
     * @param int $count
     * @dataProvider countableDataProvider
     */
    public function testCountable($itemsData, $count)
    {
        $items = new FoodItems($itemsData);
        $this->assertCount($count, $items);
    }

    public function itemsDataProvider()
    {
        return array(
            array(
                array(
                    array(
                        'name' => 'bread',
                        'amount' => 2,
                        'unit' => FoodItem::UNIT_SLICES,
                    ),
                    array(
                        'name' => 'cheese',
                        'amount' => 2,
                        'unit' => FoodItem::UNIT_SLICES,
                    ),
                    array(
                        'name' => 'peanut butter',
                        'amount' => 20,
                        'unit' => FoodItem::UNIT_GRAMS,
                    ),
                ),
            ),
        );
    }

    /**
     * @param array|Item[] $itemsData
     * @dataProvider itemsDataProvider
     */
    public function testIterator($itemsData)
    {
        $items = new FoodItems($itemsData);
        /** @var Item $item */
        foreach ($items as $name => $item) {
            $this->assertEquals('Acme\DemoBundle\Model\FoodItem', get_class($item));
            $this->assertEquals($name, $item->getName());
        }

        /** @var Item $item */
        $item = $items->rewind();
        $this->assertEquals('bread', $item->getName());
        $this->assertEquals('bread', $items->key());

        $item = $items->next();
        $this->assertEquals('cheese', $item->getName());
        $this->assertEquals('cheese', $items->key());

        $item = $items->next();
        $this->assertEquals('peanut butter', $item->getName());

        $item = $items->next();
        $this->assertFalse($item);

        $item = $items->rewind();
        $this->assertEquals('bread', $item->getName());
    }

    /**
     * @param array|Item[] $itemsData
     * @dataProvider itemsDataProvider
     */
    public function testArrayAccess($itemsData)
    {
        $items = new FoodItems($itemsData);

        $this->assertTrue(isset($items['bread']));

        $items[] = new FoodItem(array(
            'name' => 'chicken',
            'amount' => 500,
            'unit' => FoodItem::UNIT_GRAMS,
        ));

        $this->assertEquals('chicken', (string) $items['chicken']);

        unset($items['chicken']);

        $this->assertFalse(isset($items['chicken']));
    }
} 