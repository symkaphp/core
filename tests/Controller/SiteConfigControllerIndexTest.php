<?php
declare(strict_types=1);
/*
 * run test
 * php bin/phpunit symka/core/tests/Controller/SiteConfigControllerIndexTest
 * https://symfony.com/doc/4.4/testing/http_authentication.html
 */
namespace Symka\Core\Tests\Controller;


class SiteConfigControllerIndexTest extends AbstractTestController
{
    const H1_INDEX_PAGE = 'Site Config';

    const BUTTON_CREATE_NEW = 'Create New';
    const H1_PAGE_UPDATE = 'Update';
    const DATA_IS_EMPTY = 'Test Data Is Empty';

    public function testH1(): void
    {
        $clientAndCrawlerIndexPage = $this->getClientAndCrawlerIndexPage();
        $this->assertByConstant('H1_INDEX_PAGE', $clientAndCrawlerIndexPage['crawler']);
        return;
    }

    public function testDataIsEmpty(): void
    {
        $clientAndCrawlerIndexPage = $this->getClientAndCrawlerIndexPage();
        $this->assertByConstant('DATA_IS_EMPTY', $clientAndCrawlerIndexPage['crawler']);
        return;
    }

    public function testClickCreateNewButton(): void
    {
        $clientAndCrawlerIndexPage = $this->getClientAndCrawlerIndexPage();

        $this->clickLinkAndAssertPageByConstant(
            'Symka\\Core\\Tests\\Controller\\SiteConfigControllerCreateTest::H1_PAGE_CREATE_NEW',
            $clientAndCrawlerIndexPage['client'],
            $clientAndCrawlerIndexPage['crawler'],
            'site.config.controller.create',
            'Symka\Core\Controller\SiteConfigController::save'
            );
        return;
    }

    public function testClickBasketButton(): void
    {
        $clientAndCrawlerIndexPage = $this->getClientAndCrawlerIndexPage();

        $this->clickLinkAndAssertPageByConstant(
            'Symka\Core\Tests\Controller\DeletedItemsBasketControllerTest::H1_INDEX_PAGE',
            $clientAndCrawlerIndexPage['client'],
            $clientAndCrawlerIndexPage['crawler'],
            'deleted.items.basket.controller.index',
            'Symka\Core\Controller\DeletedItemsBasketController::index'
        );
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

    protected function getPageUri(): string
    {
        return AdminControllerIndexTest::ADMIN_PANEL_URL.'/site-config';
    }

    protected function assertRouteAndController(): void
    {
        $this->assertSelectorTextContains('#test-info-controller-route', 'site.config.controller.index');
        $this->assertSelectorTextContains('#test-info-controller-name', 'Symka\Core\Controller\SiteConfigController::index');
    }


}