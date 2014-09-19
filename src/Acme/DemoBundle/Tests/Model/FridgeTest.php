<?php
namespace Acme\DemoBundle\Model;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Acme\DemoBundle\Model\Recipes;
use Acme\DemoBundle\Model\FoodItem;
use Acme\DemoBundle\Model\FoodItems;

class FridgeTest extends WebTestCase
{
    /** @var Fridge */
    private $fridge;

    public function setup()
    {
        $recipes = new Recipes();
        $recipes->loadFromJSON(file_get_contents(__DIR__.'/recipes.json'));

        $items = new FridgeItems();
        $items->loadFromCSVFile(__DIR__.'/fridge.csv');

        $this->fridge = new Fridge($items);
    }

    public function testItemCount()
    {
        $items = $this->fridge->getItems();
        $this->assertCount(6, $items);
    }

    public function getMatchingItemsDataProvider()
    {
        return array(
            array(
                new FoodItems(
                    array(
                        array(
                            'name' => 'bread',
                            'amount' => 2,
                            'unit' => FoodItem::UNIT_SLICES,
                        ),
                        array(
                            'name' => 'avocado',
                            'amount' => 1,
                            'unit' => FoodItem::UNIT_OF,
                        ),
                    )
                ),
                false,
            ),
            array(
                new FoodItems(
                    array(
                        array(
                            'name' => 'bread',
                            'amount' => 2,
                            'unit' => FoodItem::UNIT_SLICES,
                        ),
                        array(
                            'name' => 'butter',
                            'amount' => 10,
                            'unit' => FoodItem::UNIT_GRAMS,
                        ),
                        array(
                            'name' => 'cheese',
                            'amount' => 1,
                            'unit' => FoodItem::UNIT_SLICES,
                        ),
                    )
                ),
                array('bread', 'butter', 'cheese'),
            ),
            array(
                new FoodItems(
                    array(
                        array(
                            'name' => 'bread',
                            'amount' => 12,
                            'unit' => FoodItem::UNIT_SLICES,
                        ),
                    )
                ),
                false,
            ),
        );
    }

    /**
     * @param FoodItems $ingredients
     * @param $matchingItemNames
     * @dataProvider getMatchingItemsDataProvider
     */
    public function testGetMatchingItems(FoodItems $ingredients, $matchingItemNames)
    {
        $fridgeItems = $this->fridge->getMatchingItems($ingredients);

        if ($matchingItemNames === false) {
            $this->assertEquals($matchingItemNames, $fridgeItems);
        } else {
            $this->assertEquals($matchingItemNames, $fridgeItems->itemNames());
        }
    }

    public function exceptionDataProvider()
    {
        return array(
            array(
                new FoodItem(
                    array(
                        'name' => 'avocado',
                        'amount' => 1,
                        'unit' => FoodItem::UNIT_OF,
                    )
                ),
                'Acme\DemoBundle\Model\Exceptions\ItemNotExists',
            ),
            array(
                new FoodItem(
                    array(
                        'name' => 'vegemite',
                        'amount' => 5,
                        'unit' => FoodItem::UNIT_GRAMS,
                    )
                ),
                'Acme\DemoBundle\Model\Exceptions\ItemExpired',
            ),
            array(
                new FoodItem(
                    array(
                        'name' => 'bread',
                        'amount' => 12,
                        'unit' => FoodItem::UNIT_SLICES,
                    )
                ),
                'Acme\DemoBundle\Model\Exceptions\InsufficientQuantity',
            ),
        );
    }

    /**
     * @param $foodItem
     * @param $expectedException
     * @dataProvider exceptionDataProvider
     */
    public function testExceptions($foodItem, $expectedException)
    {
        try {
            $this->fridge->check($foodItem);
            $this->fail('Failed to throw expected exception: ' . $expectedException);

        } catch (\Exception $e) {
            $this->assertEquals($expectedException, get_class($e));
        }
    }
}
