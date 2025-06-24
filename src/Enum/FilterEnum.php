<?php

namespace App\Enum;

class FilterEnum
{
    public const DEFAULT_ORDER_FIELD = 'createdAt';
    public const DEFAULT_ORDER_DIRECTION = self::ORDER_DIRECTION_DESC;
    public const ORDER_DIRECTION_DESC = 'DESC';
    public const ORDER_DIRECTION_ASC = 'ASC';
    public const DEFAULT_PAGE_LIMIT = 3;
    public const FIRST_PAGE = 0;

}
