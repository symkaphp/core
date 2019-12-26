<?php
/*
 * run test
 * php bin/phpunit symka/core/tests/Controller/SiteConfigControllerTest
 * https://symfony.com/doc/4.4/testing/http_authentication.html
 */
namespace Symka\Core\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SiteConfigControllerTest extends WebTestCase
{
    public function testIndexActionWithEmptyData()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/site-config');
       // $d = $crawler->filter('#test-info-controller-name');
        /*
         * Проверяем роутинг и экшин контроллера
         */
        $this->assertSelectorTextContains('#test-info-controller-route', 'symka_core_admin_site_config_index');
        $this->assertSelectorTextContains('#test-info-controller-name', 'Symka\Core\Controller\SiteConfigController::index');
        //dump($d->html()); die;
        //dump(get_class_methods($d)); die;
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


}