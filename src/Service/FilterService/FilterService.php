<?php

namespace App\Service\FilterService;

use App\Enum\FilterEnum;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

class FilterService implements FilterServiceInterface
{
    public function __construct(private readonly RequestStack $requestStack) {}

    public function apply(QueryBuilder $qb): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        $this->applySorting(
            $qb,
            $request->query->get('orderBy'),
            $request->query->get('direction')
        );

        $this->applyPagination(
            $qb,
            $request->query->get('limit', FilterEnum::DEFAULT_PAGE_LIMIT),
            $request->query->get('page', FilterEnum::FIRST_PAGE)
        );
    }

    private function applySorting(QueryBuilder $qb, ?string $orderBy, ?string $direction): void
    {
        $alias = $qb->getRootAliases()[0];

        $orderBy = $orderBy ?? FilterEnum::DEFAULT_ORDER_FIELD;
        $direction = strtoupper($direction ?? FilterEnum::DEFAULT_ORDER_DIRECTION);

        if (!in_array($direction, [
            FilterEnum::ORDER_DIRECTION_ASC,
            FilterEnum::ORDER_DIRECTION_DESC
        ], true)) {
            $direction = FilterEnum::DEFAULT_ORDER_DIRECTION;
        }

        $qb->orderBy($alias . '.' . $orderBy, $direction);
    }


    private function applyPagination(QueryBuilder $qb, mixed $limit, mixed $page): void
    {
        $limit = is_numeric($limit) ? min((int)$limit, FilterEnum::DEFAULT_PAGE_LIMIT) : FilterEnum::DEFAULT_PAGE_LIMIT;
        $page = is_numeric($page) && (int)$page > 0 ? (int)$page : FilterEnum::FIRST_PAGE;
        $offset = $page * $limit;

        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);
    }

}
