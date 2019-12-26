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
use Symka\Core\Helper\HelperCrud;
use Symka\Core\Interfaces\CrudControllerInterface;
use Symka\Core\Interfaces\CrudGridInterface;
use Symka\Core\Interfaces\CrudEntityInterface;


abstract class AbstractCrudController extends AbstractController implements CrudControllerInterface, CrudEntityInterface
{
    protected string $entityClass;
    protected string $formClass;
    protected string $redirectAfterSaveRoute;

    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $result = [];

        if ($this instanceof CrudGridInterface) {
            $result['paginator'] = $paginator->paginate($this->getGridQuery(), $request->query->getInt('page', 1));
        }

        return $this->render(HelperCrud::getViewPathByFunctionName($this, __FUNCTION__), $result);
    }

    public function save(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger, ?int $id = null): Response
    {
        $result = [];
        try {

            if ($id !== null && $id > 0) {
                $entity = $entityManager->find($this->entityClass, $id);
                if (!$entity) {
                    throw $this->createNotFoundException();
                }
            } else {
                $entity = new $this->entityClass();
            }

            $form = $this->createForm($this->formClass, $entity);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $formData = $form->getData();
                    try {
                        $entityManager->beginTransaction();
                        if ($id === null) {
                            $entityManager->persist($formData);
                        }
                        $entityManager->flush();
                        $entityManager->commit();

                    } catch (\Exception $e) {
                        $entityManager->rollback();
                        throw new CrudControllerException($e->getMessage(), $e->getCode());
                    }
                } else {
                    throw new CrudControllerException(CrudControllerException::ERROR_VALIDATE_MESSAGE, CrudControllerException::ERROR_VALIDATE);
                }
            }
            return $this->redirectToRoute($this->redirectAfterSaveRoute, ['id' => $id]);

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

        return $this->render(HelperCrud::getViewPathByFunctionName($this, __FUNCTION__), $result);
    }
}