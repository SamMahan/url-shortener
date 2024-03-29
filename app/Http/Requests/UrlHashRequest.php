<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class URlHashRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => ['required', 'active_url']
        ];
    }
}