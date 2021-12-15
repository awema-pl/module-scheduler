<?php

namespace AwemaPL\Sceduler\User\Sections\Schedules\Services;

use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models\Xmlceneo;
use AwemaPL\Storage\User\Sections\Sources\Services\Contracts\SourceType as SourceTypeContract;
use AwemaPL\Xml\User\Sections\Sources\Models\Source;
use InvalidArgumentException;

class SchedulableType
{
    /**
     * @var array  Source::class =>[
     * 'default_name' =>'Storage Source',
     * 'key' => 'storage_source',
     * ]
     */
    public static $types = [];

    /**
     * Get types
     *
     * @return array
     */
    public function getTypes()
    {
        return self::TYPES;
    }

    /**
     * Get type
     *
     * @param $type
     * @return string
     */
    public function getType($type)
    {
        if (!isset($this->getTypes()[$type])){
            throw new InvalidArgumentException("Type not exist $type");
        }
        return $type;
    }

    /**
     * Get name
     *
     * @param $type
     * @return string
     */
    public function getName($type){
        $type = $this->getType($type);
        $key = $this->getTypes()[$type]['key'];
        $defaultName = $this->getTypes()[$type]['default_name'];
        return  _p('scheduler::pages.user.schedule.types.' . $key, $defaultName);
    }
}
