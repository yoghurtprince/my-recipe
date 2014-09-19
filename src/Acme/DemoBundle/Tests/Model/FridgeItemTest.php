<?php
namespace Acme\DemoBundle\Model;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FridgeItemTest extends WebTestCase
{
    public function useByDataProvider()
    {
        return array(
            array(
                'invalid date'
            ),
        );
    }

    /**
     * @param mixed $useBy
     * @dataProvider useByDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidUseByException($useBy)
    {
        new FridgeItem(
            array(
                'name' => 'salami',
                'amount' => 5,
                'unit' => FridgeItem::UNIT_SLICES,
                'useBy' => $useBy,
            )
        );
    }
}
