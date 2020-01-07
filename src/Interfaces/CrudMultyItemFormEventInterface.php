<?php
declare(strict_types=1);

namespace Symka\Core\Interfaces;


use Symfony\Component\Form\FormInterface;

interface CrudMultyItemFormEventInterface
{
    public const NAME = 'crud.multy.item.form.event';
    public function __construct(FormInterface $form);

    public function getForm() : FormInterface;
}