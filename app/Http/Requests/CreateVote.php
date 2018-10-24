<?php

namespace App\Http\Requests;

use Debugbar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use App\Models\Voting;
use Illuminate\Support\Facades\Log;

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

        if (Request::isMethod('post')) {

            Validator::make(Request::all(), [
                'voting_matchup_id' => 'required|integer',
                'voted_on' => 'nullable|integer'
            ])->validate();

            $matchUpLookUp = $ballot->matchups()->where('id', Request::input('voting_matchup_id'))->get()->first();

            Validator::make(Request::all(), [
                'voting_matchup_id' => [
                    function ($attribute, $value, $fail) use ($matchUpLookUp) {
                        if ($matchUpLookUp === null) {
                            return $fail('Voting match-up does not belong to the voting ballot');
                        }
                    }
                ]
            ])->validate();

            Validator::make(Request::all(), [
                'voted_on' => [
                    function ($attribute, $value, $fail) use ($matchUpLookUp) {
                        if (!in_array(Request::input('voted_on'), [$matchUpLookUp->songA_id, $matchUpLookUp->songB_id, null])) {
                            return $fail('Invalid outcome');
                        }
                    }
                ]
            ])->validate();

        }

        return [];
    }
}
