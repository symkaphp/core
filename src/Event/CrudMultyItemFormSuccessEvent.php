<?php
declare(strict_types=1);

namespace Symka\Core\Event;

use Symfony\Component\Form\FormInterface;
use Symka\Core\Interfaces\CrudMultyItemFormSuccessEventInterface;

class CrudMultyItemFormSuccessEvent  implements CrudMultyItemFormSuccessEventInterface
{
    private FormInterface $form;
    private array $idArray;
    private string $entityClassName;

    public function __construct(FormInterface $form, string $entityClassName, array $id)
    {
        $this->form = $form;
        $this->idArray = $id;
        $this->entityClassName = $entityClassName;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getIdArray(): array
    {
        return $this->idArray;
    }

    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

}