<?php

namespace Modules\Attachments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveAttachmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->has('resize') && (bool) $this->get('resize')) {
            return [
                'attachable_id'      => 'required|int',
                'attachable_type'    => 'required|string',
                'name'               => 'required|string',
                'type'               => 'required|string',
                'extension'          => 'required|string',
                'mime_type'          => 'required|string',
                'size'               => 'required|integer',
                'disk'               => 'required|string',
                'basename'           => 'required|string',
                'position'           => 'required|integer',
                'width'              => 'required|integer',
                'height'             => 'required|integer'
            ];
        }

        return [
            'attachable_id'      => 'required|integer',
            'attachable_type'    => 'required|string',
            'name'               => 'required|string',
            'type'               => 'required|string',
            'extension'          => 'required|string',
            'mime_type'          => 'required|string',
            'size'               => 'required|integer',
            'disk'               => 'required|string',
            'basename'           => 'required|string',
            'position'           => 'required|integer',
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
