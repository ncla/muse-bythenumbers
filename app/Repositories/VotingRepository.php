<?php

namespace App\Repositories;

use App\Models\Voting;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class VotingRepository
 * @package App\Repositories
 * @version May 7, 2018, 12:44 am UTC
 *
 * @method Voting findWithoutFail($id, $columns = ['*'])
 * @method Voting find($id, $columns = ['*'])
 * @method Voting first($columns = ['*'])
*/
class VotingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'songs',
        'is_open',
        'expires_on',
        'type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Voting::class;
    }

    /**
     * Overriding this because the BaseRepository is unclear about the updateRelations method,
     * there might be a bug, or it's unclear how to pass the data for a relation
     * Method Illuminate\Database\Eloquent\Collection::save does not exist.
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model = \Prettus\Repository\Eloquent\BaseRepository::create($attributes);
        $this->skipPresenter($temporarySkipPresenter);

        // $model = $this->updateRelations($model, $attributes);
        $model->save();

        $model->songs()->createMany($attributes['songs']);

        return $this->parserResult($model);
    }

    public function update(array $attributes, $id)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model = \Prettus\Repository\Eloquent\BaseRepository::update($attributes, $id);
        $this->skipPresenter($temporarySkipPresenter);

        // $model = $this->updateRelations($model, $attributes);
        $model->save();

        return $this->parserResult($model);
    }

}
