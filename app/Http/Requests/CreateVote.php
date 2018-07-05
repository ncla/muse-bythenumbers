<?php

namespace App\Http\Requests;

use Debugbar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use App\Models\Voting;

class CreateVote extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $ballot = Voting::findOrFail(Request::route('id'));

        if (Request::input('voted_on') !== null) {

            Validator::make(Request::all(), [
                'voting_matchup_id' => 'required|integer',
                'voted_on' => 'required|integer'
            ])->validate();

            $matchUpLookUp = $ballot->matchups()->where('id', Request::input('voting_matchup_id'))->get()->first();

            Validator::make(Request::all(), [
                'voting_matchup_id' => [
                    function ($attribute, $value, $fail) use ($matchUpLookUp) {
                        if ($matchUpLookUp === null) {
                            return $fail('Voting match-up does not belong to the voting ballot.');
                        }
                    }
                ]
            ])->validate();

            Validator::make(Request::all(), [
                'voted_on' => [
                    function ($attribute, $value, $fail) use ($matchUpLookUp) {
                        if (!in_array(Request::input('voted_on'), [$matchUpLookUp->songA_id, $matchUpLookUp->songB_id])) {
                            return $fail('Song ID does not match one of the two possible song IDs in the match-up.');
                        }
                    }
                ]
            ])->validate();

        }

        return [];
    }
}
