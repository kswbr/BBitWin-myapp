<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface CampaignRepositoryInterface
{

    /**
     * FindOrFail Model and return the instance By Code,Project.
     *
     * @param string $project
     * @param string $code
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getByProjectAndCode($project,$code);

    /**
     * Get Query For Where Project.
     *
     * @param string $project
     *
     * @return \Illuminate\Database\Eloquent\Builder
     *
     */
    public function getProjectQuery($project);



}
