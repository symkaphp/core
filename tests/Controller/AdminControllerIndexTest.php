<?php
declare(strict_types=1);
/*
 * run test
 * php bin/phpunit symka/core/tests/Controller/AdminPanelIndexPageTest
 * https://symfony.com/doc/4.4/testing/http_authentication.html
 */

namespace Symka\Core\Tests\Controller;


class AdminControllerIndexTest extends AbstractTestController
{
    const ADMIN_PANEL_URL = '/admin';
    const H1_INDEX_PAGE = 'Test Admin Panel';
    const LINK_MAIN_PAGE_TEXT = self::H1_INDEX_PAGE;

    public function testIndexPage(): void
    {
        $this->getClientAndCrawlerIndexPage();
        return;
    }

    protected function getPageUri(): string
    {
        return self::ADMIN_PANEL_URL.'/';
    }

    protected function assertRouteAndController(): void
    {
        $this->assertSelectorTextContains('h1', self::H1_INDEX_PAGE);
        $this->assertSelectorTextContains('#test-info-controller-route', 'symka_core_admin_index');
        $this->assertSelectorTextContains('#test-info-controller-name', 'Symka\Core\Controller\AdminController::index');

    }
}