<?php


namespace Symka\Core\Interfaces;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormTypeInterface;

interface CrudSaveInterface
{
    public function formData(CrudEntityInterface $data) : void;
    public function getFormType(): FormTypeInterface;
}