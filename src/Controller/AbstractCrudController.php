<?php
declare(strict_types=1);

namespace Symka\Core\Controller;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symka\Core\Exception\CrudControllerException;
use Symka\Core\Interfaces\CrudControllerInterface;
use Symka\Core\Interfaces\CrudGridInterface;
use Symka\Core\Interfaces\CrudEntityInterface;


abstract class AbstractCrudController extends AbstractController implements CrudControllerInterface, CrudEntityInterface
{
    public function indexAction(Request $request, PaginatorInterface $paginator): Response
    {
        $result = [];

        if ($this instanceof CrudGridInterface) {
            $result['paginator'] = $paginator->paginate($this->getGridQuery(), $request->query->getInt('page', 1));
        }

        return $this->render($this->getTemplateDir(__FUNCTION__), $result);
    }

    public function saveAction(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger, ?int $id = null): Response
    {
        $result = ['form' => null];

        if ($this instanceof CrudSaveInterface) {
            try {
                $entity = $this->getEntity($entityManager, $id);
                $form = $this->save($request, $entity, $entityManager);
                return $this->redirectToRoute($this->getRouteRedirectAfterSave($id));
            } catch (CrudControllerException $crudControllerException) {
                if ($crudControllerException->getCode() != CrudControllerException::ERROR_VALIDATE) {
                    $logger->error("Error Save Crud ", [
                        "class" => get_class($this),
                        "code" => $crudControllerException->getCode(),
                        "message" => $crudControllerException->getMessage()
                    ]);
                }
            } catch (\Exception $exception) {
                throw $exception;
            }

            $result = [
                'form' => $form
            ];
        }

        return $this->render($this->getTemplateDir(__FUNCTION__), $result);

    }

    public function getEntity(EntityManagerInterface $entityManager, ?int $id): CrudEntityInterface
    {
        $entityClassName = $this->getClassName();
        if (!empty($id) && $id > 0) {
            $entity = $entityManager->find($entityClassName, $id);
            if (!$entity) {
                throw $this->createNotFoundException();
            }
        } else {
            $entity = new $entityClassName();
        }

        return $entity;
    }

    protected function save(Request $request, CrudEntityInterface $entityCrud, EntityManagerInterface $entityManager): FormTypeInterface
    {
        $formType = $this->getFormType();
        $form = $this->createForm(get_class($formType), $entityCrud);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $formData = $form->getData();
                $this->formData($formData);
                try {
                    $entityManager->beginTransaction();
                    $entityManager->persist($formData);
                    $entityManager->flush();
                    $entityManager->commit();
                    return true;
                } catch (\Exception $e) {
                    $entityManager->rollback();
                    throw new CrudControllerException($e->getMessage(), $e->getCode());
                }
            } else {
                throw new CrudControllerException(CrudControllerException::ERROR_VALIDATE_MESSAGE, CrudControllerException::ERROR_VALIDATE);
            }
        }
        return $formType;
    }

    public function formData(CrudEntityInterface $data): void
    {

    }

    public function getDefaultTemplateNamespace(): string
    {
        return '@SymkaCoreBundle/Resource/views';
    }

    public function getTemplateDir(string $actionName): string
    {
        $classShortName = (new \ReflectionClass($this))->getShortName();
        $classShortName = str_replace('Controller', '', $classShortName);
        $classShortName = lcfirst($classShortName);

        $templateFileName = str_replace('Action', '', $actionName);

        return $this->getDefaultTemplateNamespace() . '/' . $classShortName . '/' . $templateFileName . $this->getDefaultTemplateExtension();
    }

    public function getDefaultTemplateExtension(): string
    {
        return ".html.twig";
    }
}