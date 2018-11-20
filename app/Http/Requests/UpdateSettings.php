<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettings extends FormRequest
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
        return [
            'settings.voting_embed' => 'required|numeric|between:0,2',
            'settings.voting_progressbar' => 'required|numeric|between:0,1'
        ];
    }

    public function attributes()
    {
        return [
            'settings.voting_embed' => 'voting embed setting',
            'settings.voting_progressbar' => 'voting progress bar setting'
        ];
    }
}
