<?php
namespace Acme\DemoBundle\Model;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class FridgeItemsTest extends WebTestCase
{
    /** @var Items|Item[] */
    private $items;

    public function setup()
    {
        $this->items = new FridgeItems();
        $this->items->loadFromCSVFile(__DIR__.'/fridge.csv');
    }

    public function testNearestUseBy()
    {
        $oneMinLater = strtotime('+1 minute');
        $this->items['bread']->setUseBy($oneMinLater);

        $this->assertEquals($oneMinLater, $this->items->getNearestUseBy());
    }
}
