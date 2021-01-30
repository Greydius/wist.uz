<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolYearRequest extends FormRequest
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
            'first_trimester_start_date' => 'required|date_format:Y-m-d',
            'first_trimester_end_date' => 'required|date_format:Y-m-d',
            'second_trimester_start_date' => 'required|date_format:Y-m-d',
            'second_trimester_end_date' => 'required|date_format:Y-m-d',
            'third_trimester_start_date' => 'required|date_format:Y-m-d',
            'third_trimester_end_date' => 'required|date_format:Y-m-d'
        ];
    }
}
