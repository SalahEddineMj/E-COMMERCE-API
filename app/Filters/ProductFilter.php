<?php

namespace App\Filters;

use App\Filters\ApiFilter;


class ProductFilter extends ApiFilter {
    protected $safeParams = [
        'price' => ['eq', 'lt', 'gt', 'lte', 'gte']
    ];



    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

}
