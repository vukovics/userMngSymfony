<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    public function testSomething()
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}
