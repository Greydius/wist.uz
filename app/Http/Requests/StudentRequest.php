<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'birthdate' => ['required', 'date_format:Y-m-d'],
            'visit_date' => ['nullable', 'date_format:Y-m-d'],

            'application' => [Rule::in('unfilled', 'filled', 'filled-online')],
            'application_date' => ['nullable', 'date_format:Y-m-d'],

            'assessment' => ['nullable', 'string'],
            'assessment_date' => ['nullable', 'date_format:Y-m-d'],

            'contract' => [Rule::in('ungiven','given','done','cancelled')],
            'school_start_date' => ['nullable', 'date_format:Y-m-d'],
            'payment' => [Rule::in('unpaid', 'paid', 'paid-partly', 'cancelled')],
        ];
    }
}
