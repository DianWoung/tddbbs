<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 2018/7/13
 * Time: 14:45
 */

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request,$builder;
    protected $filters;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;
        foreach ($this->getFilters() as $filter => $value){
            if (method_exists($this, $filter)){
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    public function getFilters()
    {
        return collect($this->request)->only($this->filters);
    }
}