<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $requiredWithoutIsDraft = Rule::requiredIf(request('is_draft') == null);

        return [
            'title' => [
                $requiredWithoutIsDraft,
                'max:60',
            ],
            'content' => [
                $requiredWithoutIsDraft,
                'nullable',
            ],
            'is_draft' => [
                'nullable',
            ],
            'publish_date' => [
                $requiredWithoutIsDraft,
                $requiredWithoutIsDraft->condition == true ? 'date' : ''
            ],
        ];
    }
}
