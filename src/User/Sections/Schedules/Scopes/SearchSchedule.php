<?php

namespace AwemaPL\Scheduler\User\Sections\Schedules\Scopes;
use AwemaPL\Repository\Scopes\ScopeAbstract;

class SearchSchedule extends ScopeAbstract
{
    /**
     * Scope
     *
     * @param $builder
     * @param $value
     * @param $scope
     * @return mixed
     */
    public function scope($builder, $value, $scope)
    {
        if (!$value){
            return $builder;
        }

        return $builder->where('name', 'like', '%'.$value.'%');
    }
}
