<?php

namespace IW\EasySurveyBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testCreateuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/createUser');
    }

    public function testModifyuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifyUser');
    }

    public function testDeleteuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deleteUser');
    }

    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
    }

    public function testLogout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/logout');
    }

}
