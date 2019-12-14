<?php
//https://symfony.com/doc/4.4/testing/http_authentication.html

namespace Symka\Core\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SiteConfigControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/site-config');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}