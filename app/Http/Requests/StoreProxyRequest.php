<?php

namespace App\Http\Requests;

use App\Enums\ProxyProtocol;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProxyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'host' => [
                'required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9._-]+$/',
                Rule::unique('proxies')->where(fn ($query) => $query
                    ->where('port', $this->input('port'))
                    ->where('protocol', $this->input('protocol'))),
            ],
            'port' => ['required', 'integer', 'between:1,65535'],
            'protocol' => ['required', Rule::enum(ProxyProtocol::class)],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'host.unique' => 'Такой прокси уже есть',
            'host.regex' => 'Хост должен быть IP адресом или доменом',
        ];
    }
}
