<?php

namespace App\Http\Requests;

use App\Rules\EmailDomainValidRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class SubscriberRequest
 * @package App\Http\Requests
 */
class SubscriberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if ($this->method() == 'PATCH') {
            $unique = Rule::unique('subscribers', 'email')->ignore($this->input('id'));
        } else {
            $unique = Rule::unique('subscribers', 'email');
        }

        return [
            'email' => [
                'required',
                'email',
                new EmailDomainValidRule,
                $unique,
            ],
            'name' => [
                'required',
                'string'
            ]
        ];
    }
}
