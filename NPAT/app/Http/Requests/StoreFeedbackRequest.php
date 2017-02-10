<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreFeedbackRequest extends Request
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
            'projectTitleSelect' => 'required',
            'fromdate'           => 'required',
            'fromyear'           => 'required',
        ];
    }
    public function messages()
    {

        return [
            'projectTitleSelect.required' => 'Please Select Project',
            'resourceSelect'              => 'required',
            'fromdate'                    => 'required',
            'fromyear'                    => 'required',
        ];
    }
}
