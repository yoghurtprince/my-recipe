<?php

namespace Acme\DemoBundle\Model;

use Acme\DemoBundle\Model\Exceptions\InsufficientQuantity;
use Acme\DemoBundle\Model\Exceptions\ItemExpired;
use Acme\DemoBundle\Model\Exceptions\ItemNotExists;


class Fridge
{
    /** @var Items|Item[] */
    private $items = array();

    /**
     * @param FridgeItems $items
     */
    public function __construct(FridgeItems $items)
    {
        $this->fill($items);
    }

    /**
     * Stock the fridge with items
     *
     * @param FridgeItems $items
     */
    public function fill(FridgeItems $items)
    {
        $this->items = $items;
    }

    /**
     * Checks if our fridge has a food item for the required quantity
     * and that has not passed its use-by date.
     * Throws relevant exception if any of these conditions fail
     * Otherwise returns true
     *
     * @param FoodItem $item
     * @return bool
     * @throws InsufficientQuantity
     * @throws ItemNotExists
     * @throws ItemExpired
     */
    public function check(FoodItem $item)
    {
        $itemName = $item->getName();

        if (!isset($this->items[$itemName])) {
            throw new ItemNotExists($itemName . ' is not in this fridge.');
        }

        $fridgeItem = $this->items[$itemName];

        if ($item->getAmount() > $fridgeItem->getAmount()) {
            throw new InsufficientQuantity("There is only " . $fridgeItem->getAmount()." (".$fridgeItem->getUnit().") for item $itemName in the fridge, requested ".$item->getAmount());
        }

        if ($fridgeItem->hasExpired()) {
            throw new ItemExpired($itemName.' has expired on '.date('Y-m-d', $fridgeItem->getUseBy()));
        }

        return true;
    }

    /**
     * Checks if our fridge has a food item for the required quantity
     * and that's has not passed its use-by date
     *
     * @param FoodItem $item
     * @return bool
     */
    public function has(FoodItem $item)
    {
        try {
            $this->check($item);
        } catch (ItemNotExists $e) {
            return false;
        } catch (InsufficientQuantity $e) {
            return false;
        } catch (ItemExpired $e) {
            return false;
        }

        return true;
    }

    public function getItems()
    {
        return $this->items;
    }

    /**
     * Given a list of Food Items (ingredients)
     * If we have all items in the fridge
     *      return those fridge items
     *      otherwise returns false
     *
     * @param FoodItems $items
     * @return bool|Items
     */
    public function getMatchingItems(FoodItems $items)
    {
        $fridgeItems = new FridgeItems();
        /** @var FoodItem $item */
        foreach ($items as $item) {
            if (!$this->has($item)) {
                return false;
            }
            $itemName = $item->getName();
            $fridgeItems[] = $this->items[$itemName];
        }

        return $fridgeItems;
    }

}