<?php
/*
 * run test
 * php bin/phpunit symka/core/tests/Controller/SiteConfigControllerTest
 * https://symfony.com/doc/4.4/testing/http_authentication.html
 */
namespace Symka\Core\Tests\Controller;

use PHPUnit\Framework\AssertionFailedError;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SiteConfigControllerTest extends WebTestCase
{
    public function testIndexActionWithEmptyData()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $crawler = $client->request('GET', '/admin/site-config');
        $profile = $client->getProfile();
        $exceptionProfile = $profile->getCollector('exception');

        if ($exceptionProfile->hasException()) {
            $message = sprintf(
                "No exception was expected but got '%s' with message '%s'. Trace:\n%s",
                get_class($exceptionProfile->getException()),
                $exceptionProfile->getMessage(),
                $exceptionProfile->getException()->getTraceAsString()
            );
            throw new AssertionFailedError($message);
        }
       // $d = $crawler->filter('#test-info-controller-name');
        /*
         * Проверяем роутинг и экшин контроллера
         */
        $this->assertSelectorTextContains('#test-info-controller-route', 'site.config.controller.index');
        $this->assertSelectorTextContains('#test-info-controller-name', 'Symka\Core\Controller\SiteConfigController::index');
        //dump($d->html()); die;
        //dump(get_class_methods($d)); die;



        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


}