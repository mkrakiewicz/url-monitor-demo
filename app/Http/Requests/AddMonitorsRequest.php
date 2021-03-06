<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMonitorsRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'items' => 'required|array',
            'stats' => 'boolean'
        ];
    }
}
