<?php
declare(strict_types=1);

namespace Symka\Core\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\AssertionFailedError;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symka\Core\Interfaces\SymkaWebTestInterface;

abstract class AbstractTestController  extends WebTestCase implements SymkaWebTestInterface
{

    protected ?EntityManagerInterface $entityManager = null;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function getClientAndCrawlerIndexPage(): array
    {
        $client = static::createClient();
        $client->enableProfiler();
        $crawler = $client->request('GET', $this->getPageUri());
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

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRouteAndController();


        return [
            'client' => $client,
            'crawler' => $crawler
        ];
    }

    protected function clickLinkAndAssertPageByConstant(string $constantName, KernelBrowser $client, Crawler $crawler, ?string $controllerClassName = null, ?string $route = null): void
    {
        $crawler = $this->assertByConstant($constantName, $crawler);
        $page = $client->click($crawler->link());
        $this->assertByConstant($constantName, $page);

        if (!empty($controllerClassName)) {
            $this->assertEquals($page->filter('#test-info-controller-route')->first()->text(), $controllerClassName);
        }

        if (!empty($route)) {
            $this->assertEquals($page->filter('#test-info-controller-name')->first()->text(), $route);
        }

        return;
    }

    protected function assertByConstant(string $constantName, Crawler $crawler): Crawler
    {
        if (defined($constantName)) {
            $selector = $this->getSelectorByPathConstant($constantName);
        } else {
            $this->assertTrue(defined(get_class($this).'::'.$constantName));
            $selector = $this->getSelectorByConstant($constantName);
        }
        $return = $crawler->filter($selector);

        $this->assertTrue($return->count() > 0, $selector .' Not Found');
        return $return;
    }

    private function getSelectorByPathConstant(string $constantName): string
    {
        list(, $actionName_) = explode('::', $constantName);
        return '['.strtolower($actionName_).'="'.constant($constantName).'"]';
    }

    protected function getSelectorByConstant(string $constantName): string
    {
        return '['. strtolower($constantName) .'="'.constant(get_class($this).'::'.$constantName).'"]';
    }

    protected function clearTable(string $tableName): void
    {
        $this->entityManager->getConnection()->executeQuery("TRUNCATE $tableName");
    }

    abstract protected function getPageUri(): string;
    abstract protected function assertRouteAndController(): void;
}