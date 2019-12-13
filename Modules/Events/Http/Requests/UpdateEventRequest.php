<?php

namespace Modules\Events\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'             => 'required|string|min:4|max:255',
            'description'       => 'required|string|min:4',
            'address'           => 'required|string|min:4',
            'user_id'           => 'required|integer',
            'start'             => 'required|date',
            'end'               => 'required|date',
            'color'             => 'required|string',
            'online'            => 'required|boolean',
            'categories'        => 'required|array|min:1',
            'tags'              => 'array',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
