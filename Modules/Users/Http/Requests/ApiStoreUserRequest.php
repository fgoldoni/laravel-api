<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiStoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'first_name'       => 'nullable|string',
            'last_name'        => 'nullable|string',
            'email'            => 'required|string|email|max:255|unique:users',
            'mobile'           => 'nullable|regex:#^[0\+]{1}[0-9-]{8,15}#',
            'phone'            => 'nullable|regex:#^[0\+]{1}[0-9-]{8,15}#',
            'password_confirm' => 'required|min:6|required_with:password',
            'password'         => 'required|sometimes|required_with:password_confirm|same:password_confirm|min:6',
            'roles'            => 'required|array',
        ];

        if ($this->request->has('roles')) {
            foreach ($this->request->get('roles') as $key => $val) {
                $rules['roles.' . $key] = 'required|integer';
            }
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
