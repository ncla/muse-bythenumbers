<?php

namespace App\Repositories;

use App\Models\Songs;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SongsRepository
 * @package App\Repositories
 * @version April 19, 2018, 10:24 pm UTC
 *
 * @method Songs findWithoutFail($id, $columns = ['*'])
 * @method Songs find($id, $columns = ['*'])
 * @method Songs first($columns = ['*'])
*/
class SongsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mbid',
        'name',
        'name_override',
        'manually_added',
        'is_utilized'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Songs::class;
    }
}
