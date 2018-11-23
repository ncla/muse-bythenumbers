<?php

namespace App\Repositories;

use App\Models\Setlist;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SetlistRepository
 * @package App\Repositories
 * @version July 1, 2018, 1:19 pm UTC
 *
 * @method Setlist findWithoutFail($id, $columns = ['*'])
 * @method Setlist find($id, $columns = ['*'])
 * @method Setlist first($columns = ['*'])
*/
class SetlistRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date',
        'venue' => 'LIKE',
        'songs.name' => '=',
        'songs.note' => 'LIKE'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Setlist::class;
    }

    public function findWithoutFailWithTrashed($id, $columns = ['*'])
    {
        $this->scopeQuery(function($query) {
            return $query->withTrashed();
        });

        try {
            return $this->find($id, $columns);
        } catch (Exception $e) {
            return;
        }
    }
}
