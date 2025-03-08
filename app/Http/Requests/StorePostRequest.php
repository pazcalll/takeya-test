<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
                'date',
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_draft' => $this->has('is_draft') && $this->input('is_draft') == 'on',
            'publish_date' => $this->input('publish_date') ?? null,
            'title' => Str::trim($this->input('title')),
            'content' => Str::trim($this->input('content')),
        ]);
    }
}
