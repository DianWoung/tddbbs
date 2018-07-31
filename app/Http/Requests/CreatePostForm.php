<?php

namespace App\Http\Requests;

use App\Reply;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\ThrottleException;

class CreatePostForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new Reply());
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|spamfree'
        ];
    }

    public function messages()
    {
        return [
            'body.required' => 'A body is required',
            'body.spamfree' => 'the content contains spam words',
        ];
    }
}
