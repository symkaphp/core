<?php
declare(strict_types=1);

namespace Symka\Core\Controller;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symka\Core\Exception\CrudControllerException;
use Symka\Core\Interfaces\EntityCrudInterface;
use Symka\Core\PaginatorInterface;

abstract class AbstractCrudController extends AbstractController
{

    protected function save(EntityCrudInterface $entity, Request $request, FormTypeInterface $formType, EntityManagerInterface $entityManager) : FormTypeInterface
    {
        $form = $this->createForm(get_class($formType), $entity);
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

    protected function formData(EntityCrudInterface $data)
    {

    }
}