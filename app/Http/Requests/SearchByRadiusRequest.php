<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchByRadiusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius' => 'required|numeric|min:0.1',
        ];
    }
}