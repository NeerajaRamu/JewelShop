<?php namespace App\Repositories;

use App\Repositories\DeploymentRepository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseRepository extends Repository implements RepositoryInterface
{

    public $errors;

    public function model()
    {
    }

    public function getRules()
    {
        return $this->model->rules();
    }


    /**
     * @param       $id
     * @param array $columns
     * @return mixed
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $result = $this->find($id, $columns);

        if (is_array($id)) {
            if (count($result) == count(array_unique($id))) {
                return $result;
            }
        } elseif (!is_null($result)) {
            return $result;
        }

        throw (new ModelNotFoundException)->setModel(get_class($this->model));
    }

    /**
     * findByExternal look up by external ID
     * @param  [string] $externalId
     * @return [object] eloquent model object
     */
    public function findByExternal($externalId)
    {
        return $this->where('external_id', $externalId)->firstOrFail();
    }

    public function currentDeployment()
    {
        return app()->make(DeploymentRepository::class)->current();
    }
}
