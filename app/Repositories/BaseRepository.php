<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    public function __construct(private readonly ?Model $model)
    {
        //
    }

    public function query(): Builder|\Illuminate\Database\Query\Builder
    {
        if (empty($this->model)) {
            return DB::table('')->newQuery();
        }

        return $this->model->newQuery()->setEagerLoads([]);
    }

    public function filter(array $filters): Builder|\Illuminate\Database\Query\Builder
    {
        $query = $this->query();

        foreach ($filters as $col => $value) {
            if (is_callable($value)) {
                // Nested query
                $query = $value($query);
                continue;
            }

            if (is_array($value)) {
                // Check for dates
                if (count($value) === 2 && (strtotime($value[0]) && strtotime($value[1]))) {
                    $query->whereBetween($col, $value);
                    continue;
                }

                // Check array
                $query->whereIn($col, $value);
                continue;
            }

            // Check normal data
            $query->where($col, $value);
        }

        return $query;
    }
}
