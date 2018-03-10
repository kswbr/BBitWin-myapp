<?php

namespace App\Services;

use App\Repositories\CampaignRepositoryInterface;

class CampaignService
{
    protected $repository;

    public function __construct(
        CampaignRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

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
    public function saveInProject(string $name, string $code, int $limited_days, string $project)
    {
        return $this->repository->updateOrCreateOnProjectAndCode([
            "name" => $name,
            "code" => $code,
            "limited_days" => $limited_days,
            "project" => $project
        ],$project,$code);
    }

    /**
     * Delete the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

}
