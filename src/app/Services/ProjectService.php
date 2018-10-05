<?php

namespace App\Services;

class ProjectService
{
    public function __construct() { }

    /**
     * Save the model from the database.
     *
     * @param string $name
     * @param string $code
     * @param int $limited_days
     * @param string $project
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    public function getCode()
    {
        return \Cookie::get("PROJECT_NAME",env("PROJECT_NAME", null));
    }

}
