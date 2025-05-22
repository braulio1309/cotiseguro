<?php


namespace App\Filters\Core;


use App\Filters\Core\traits\StatusIdFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\UserFilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CompaniasFilter extends FilterBuilder
{
    use StatusIdFilter, UserFilterTrait;

    public function nombre($nombre = null)
    {
        $this->whereClause('nombre', "%{$nombre}%", 'LIKE');
    }


    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('nombre', 'LIKE', "%$search%");
        });
    }


}
