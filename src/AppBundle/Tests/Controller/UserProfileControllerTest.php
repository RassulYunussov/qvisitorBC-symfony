<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileControllerTest extends WebTestCase
{
    public function testUserprofile()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'userprofile');
    }

}
