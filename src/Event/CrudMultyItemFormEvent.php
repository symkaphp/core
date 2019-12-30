<?php
declare(strict_types=1);

namespace Symka\Core\Event;


use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symka\Core\Interfaces\CrudMultyItemFormEventInterface;

class CrudMultyItemFormEvent extends Event implements CrudMultyItemFormEventInterface
{
    private FormInterface $form;

    public function __construct(FormInterface $form)
    {
        $this->form = $form;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }
}