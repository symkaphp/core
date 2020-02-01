<?php


namespace Symka\Core\Twig\Extension;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symka\Core\Entity\SiteConfigEntity;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TestHelperExtension extends AbstractExtension
{
    private bool $isTestEnv = false;

    public function __construct()
    {
        $this->isTestEnv = (isset($_ENV["APP_ENV"]) && $_ENV["APP_ENV"] == "test");
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('testTag', [$this, 'testTag'])
        ];
    }

    public function testTag(string $constantName, ?string $activeController = null, ?string $actionName = null): ?string
    {
       // if ($this->isTestEnv == "test") {
            if (defined($constantName)) {

                list(, $actionName_) = explode('::', $constantName);
                return strtolower($actionName_).'="'.constant($constantName).'"';
            }

            $constantName = strtolower($constantName);
            $testClassName = $this->getClassNameByActiveController($activeController, $actionName);
            $constant = $testClassName.'::'.strtoupper($constantName);

            if (defined($constant)) {
                return strtolower($constantName).'="'.constant($constant).'"';
            }
       // }
        return null;
    }

    private function getClassNameByActiveController(string $className, ?string $actionName = null): ?string
    {
        list($controllerClassName, $actionName_) = explode('::', $className);
        $testController = str_replace('\\Controller\\', '\\Tests\\Controller\\', $controllerClassName);

        if ($actionName === null) {
            $actionName = $actionName_;
        }

        $testClassNameWithAction = $testController.ucfirst($actionName).'Test';

        if (class_exists($testClassNameWithAction)) {
            return $testClassNameWithAction;
        }

        $testClassNameWithoutAction = $testController.'Test';
        if (class_exists($testClassNameWithoutAction)) {
            return $testClassNameWithoutAction;
        }

        return null;
    }

}