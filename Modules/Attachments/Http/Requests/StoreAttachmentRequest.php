<?php

namespace Modules\Attachments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attachable_id'   => 'required|int',
            'attachable_type' => 'required|string',
            'folder'          => 'required|string',
            'attachment'      => 'required|image',
            'resize'          => 'required|boolean',
            'width'           => 'required|int',
            'height'          => 'required|int'
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
