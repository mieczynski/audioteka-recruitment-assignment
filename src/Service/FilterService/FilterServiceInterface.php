<?php

namespace App\Service\FilterService;

use Doctrine\ORM\QueryBuilder;

interface FilterServiceInterface
{
    public function apply(QueryBuilder $qb): void;
}