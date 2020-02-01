<?php
declare(strict_types=1);
/*
 * run test
 * php bin/phpunit symka/core/tests/Controller/SiteConfigControllerCreateTest
 * https://symfony.com/doc/4.4/testing/http_authentication.html
 */
namespace Symka\Core\Tests\Controller;

use Symka\Core\Entity\SiteConfigEntity;
use Symka\Core\Interfaces\SymkaWebTestInterface;



class SiteConfigControllerCreateTest extends AbstractTestController
{
    const H1_INDEX_PAGE = 'Create New';
    const RETURN_INDEX_PAGE = self::H1_INDEX_PAGE;
    const H1_INDEX_PAGE_UPDATE = 'Create New';



    public function testH1(): void
    {
        $clientAndCrawlerIndexPage = $this->getClientAndCrawlerIndexPage();
        $this->assertByConstant('H1_INDEX_PAGE', $clientAndCrawlerIndexPage['crawler']);
        return;
    }

    public function testClickSiteSettingIndexPage(): void
    {
        $clientAndCrawlerIndexPage = $this->getClientAndCrawlerIndexPage();

        $this->clickLinkAndAssertPageByConstant(
            'Symka\Core\Tests\Controller\SiteConfigControllerIndexTest::H1_INDEX_PAGE',
            $clientAndCrawlerIndexPage['client'],
            $clientAndCrawlerIndexPage['crawler'],
            'site.config.controller.index',
            'Symka\Core\Controller\SiteConfigController::index'
        );
        return;
    }

    public function testClickCancelButton(): void
    {
        $clientAndCrawlerIndexPage = $this->getClientAndCrawlerIndexPage();

        $this->clickLinkAndAssertPageByConstant(
            'Symka\Core\Tests\Controller\SiteConfigControllerIndexTest::H1_INDEX_PAGE',
            $clientAndCrawlerIndexPage['client'],
            $clientAndCrawlerIndexPage['crawler'],
            'site.config.controller.index',
            'Symka\Core\Controller\SiteConfigController::index'
        );
        return;
    }

    public function testClickByLinkAdminMainPage(): void
    {
        $clientAndCrawlerIndexPage = $this->getClientAndCrawlerIndexPage();
        $this->clickLinkAndAssertPageByConstant(
            'Symka\Core\Tests\Controller\AdminControllerIndexTest::H1_INDEX_PAGE',
            $clientAndCrawlerIndexPage['client'],
            $clientAndCrawlerIndexPage['crawler'],
            'symka_core_admin_index',
            'Symka\Core\Controller\AdminController::index'
        );
        return;
    }

    public function testCreateWithEmptyData(): void
    {
        $clientAndCrawler = $this->getClientAndCrawlerIndexPage();

        $submit1 = $clientAndCrawler['crawler']->filter('[type="submit"]')->first();
        $form = $submit1->form([
            'site_config_form[name]' => '',
            'site_config_form[domain]' => '',
            'site_config_form[status]' => '1',
            'site_config_form[templatePath]' => '',
            'site_config_form[adminTemplatePath]' => '',
        ]);

        $crawler = $clientAndCrawler['client']->submit($form);
        $this->assertByConstant('ERROR_MESSAGE', $crawler);

        $this->assertTrue(($crawler->filter('label[for="site_config_form_name"] .form-error-message')->count() > 0));
        $this->assertEquals($crawler->filter('label[for="site_config_form_name"] .form-error-message')->text(), SymkaWebTestInterface::NOT_BLANK_TEXT_EN);

        $this->assertTrue(($crawler->filter('label[for="site_config_form_domain"] .form-error-message')->count() > 0));
        $this->assertEquals($crawler->filter('label[for="site_config_form_domain"] .form-error-message')->text(), SymkaWebTestInterface::NOT_BLANK_TEXT_EN);

        $this->assertTrue(($crawler->filter('label[for="site_config_form_templatePath"] .form-error-message')->count() > 0));
        $this->assertEquals($crawler->filter('label[for="site_config_form_templatePath"] .form-error-message')->text(), SymkaWebTestInterface::NOT_BLANK_TEXT_EN);

        $this->assertTrue(($crawler->filter('label[for="site_config_form_adminTemplatePath"] .form-error-message')->count() > 0));
        $this->assertEquals($crawler->filter('label[for="site_config_form_adminTemplatePath"] .form-error-message')->text(), SymkaWebTestInterface::NOT_BLANK_TEXT_EN);

        return;
    }

    public function testSaveData(): void
    {
        if (!defined('NO_COMMIT_BY_TEST')) {
            define('NO_COMMIT_BY_TEST', 'YES');
        }

        $clientAndCrawler = $this->getClientAndCrawlerIndexPage();
        $submit1 = $clientAndCrawler['crawler']->filter('[type="submit"]')->first();
        $form = $submit1->form([
            'site_config_form[name]' => 'test name',
            'site_config_form[domain]' => 'test domain',
            'site_config_form[status]' => '1',
            'site_config_form[templatePath]' => 'test template page',
            'site_config_form[adminTemplatePath]' => 'test admin template path',
        ]);

        $clientAndCrawler['client']->submit($form);
        $crawler = $clientAndCrawler['client']->followRedirect();

        $this->assertByConstant('SUCCESS_MESSAGE', $crawler);
        return;
    }


    public function testSaveErrorDataAlreadyExists(): void
    {
        try {
            $this->entityManager->beginTransaction();
            $siteConfigEntity = new SiteConfigEntity();
            $siteConfigEntity->setName('test name');
            $siteConfigEntity->setDomain('test domain');
            $siteConfigEntity->setStatus(1);
            $siteConfigEntity->setTemplatePath('test template page');
            $siteConfigEntity->setAdminTemplatePath('test admin template path');
            $siteConfigEntity->setCreatedAt(new \DateTime('now'));

            $this->entityManager->persist($siteConfigEntity);
            $this->entityManager->flush();

            $this->testSaveData();
            $this->entityManager->rollback();
        } catch (\Exception $exception) {

        }




        return;
    }


    protected function getPageUri(): string
    {
        return AdminControllerIndexTest::ADMIN_PANEL_URL.'/site-config-create';
    }

    protected function assertRouteAndController(): void
    {
        $this->assertSelectorTextContains('#test-info-controller-route', 'site.config.controller.create');
        $this->assertSelectorTextContains('#test-info-controller-name', 'Symka\Core\Controller\SiteConfigController::save');
        return;
    }

}