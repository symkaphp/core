<?php
declare(strict_types=1);

namespace Symka\Core\Interfaces;


use Symfony\Component\Form\FormInterface;

interface CrudMultyItemFormSuccessEventInterface
{
    public const NAME = 'crud.multy.item.form.success.event';
    public function __construct(FormInterface $form, string $entityClassName, array $id);
    public function getForm(): FormInterface;
    public function getIdArray(): array;
    public function getEntityClassName(): string;

}