<?php
namespace Acme\DemoBundle\Model;

use Acme\DemoBundle\Model\Fridge;
use Acme\DemoBundle\Model\FridgeItems;


class FinderTest extends \PHPUnit_Framework_TestCase
{
    /** @var Finder */
    private $recipeFinder;

    public function setup()
    {
        $recipes = new Recipes();
        $recipes->loadFromJSON(file_get_contents(__DIR__.'/recipes.json'));

        $items = new FridgeItems();
        $items->loadFromCSVFile(__DIR__.'/fridge.csv');

        $fridge = new Fridge($items);
        $this->recipeFinder = new Finder($fridge, $recipes);
    }

    public function testValidRecipes()
    {
        $validRecipes = $this->recipeFinder->findValid();
        $this->assertEquals(array('grilled cheese on toast'), array_keys($validRecipes));
    }

    public function testCookTonight()
    {
        $this->assertEquals('grilled cheese on toast', $this->recipeFinder->findCookTonight());
    }
}
