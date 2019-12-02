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
        if ($this->has('resize') && (bool) $this->get('resize')) {
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

        return [
                'attachable_id'   => 'required|int',
                'attachable_type' => 'required|string',
                'folder'          => 'required|string',
                'attachment'      => 'required|image',
                'resize'          => 'nullable|boolean',
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
