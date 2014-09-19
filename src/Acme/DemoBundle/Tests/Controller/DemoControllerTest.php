<?php

namespace Acme\DemoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DemoControllerTest extends WebTestCase
{
    public function testIndex()
    {
    	ini_set("xdebug.max_nesting_level ", 600);
    	$client = static::createClient();
    	
    	$crawler = $client->request('GET', '/recipe');
    	
    	$this->assertGreaterThan(0, $crawler->filter('html:contains("Food Items")')->count());
    }

}
