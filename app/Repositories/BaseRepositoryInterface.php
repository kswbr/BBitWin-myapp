<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{

    /**
     * Paginate the given query.
     *
     * @param The number of models to return for pagination $n integer
     * @param search query yet not execute
     *
     * @return mixed
     */
    public function getPaginate($n, $search_query);

     /**
     * Create a new model and return the instance.
     *
     * @param array $inputs
     *
     * @return Model instance
     */
    public function store(array $inputs);

    /**
     * Get Binding Model Name
     *
     * @return string Model Name
     */
    public function getModelName();


     /**
     * FindOrFail Model and return the instance.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id);

    /**
     * Update the model in the database.
     *
     * @param $id
     * @param array $inputs
     */
   public function update($id, array $inputs);

    /**
     * Delete the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function destroy($id);

}
