<?php


namespace Symka\Core\Interfaces;


use Doctrine\ORM\EntityManagerInterface;
use Symka\Core\Entity\SiteConfigEntity;

interface CrudEntityInterface
{
    public function getId(): ?int;



}