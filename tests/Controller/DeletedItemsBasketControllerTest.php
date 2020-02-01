<?php
declare(strict_types=1);
/*
 * run test
 * php bin/phpunit symka/core/tests/Controller/DeletedItemsBasketControllerTest
 * https://symfony.com/doc/4.4/testing/http_authentication.html
 */


namespace Symka\Core\Tests\Controller;


class DeletedItemsBasketControllerTest extends AbstractTestController
{
    const H1_INDEX_PAGE = 'Test Basket';
    const DATA_IS_EMPTY = 'Test Data Is Empty';

    public function testIndexPage(): void
    {
        $clientAndCrawlerIndexPage = $this->getClientAndCrawlerIndexPage();
        $this->assertByConstant('H1_INDEX_PAGE', $clientAndCrawlerIndexPage['crawler']);
    }

    public function testDataIsEmpty()
    {
        $clientAndCrawlerIndexPage = $this->getClientAndCrawlerIndexPage();
        $this->assertByConstant('DATA_IS_EMPTY', $clientAndCrawlerIndexPage['crawler']);
    }

    protected function getPageUri(): string
    {
        return AdminControllerIndexTest::ADMIN_PANEL_URL.'/basket';
    }

    protected function assertRouteAndController(): void
    {
        $this->assertSelectorTextContains('#test-info-controller-route', 'deleted.items.basket.controller.index');
        $this->assertSelectorTextContains('#test-info-controller-name', 'Symka\Core\Controller\DeletedItemsBasketController::index');
        return;
    }

}