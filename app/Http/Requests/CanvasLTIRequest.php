<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CanvasLTIRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'oauth_consumer_key'               => 'in:' . config('canvas.consumer_key'),
            'oauth_signature_method'           => 'required',
            'oauth_timestamp'                  => 'required',
            'oauth_nonce'                      => 'required',
            'lis_person_contact_email_primary' => 'required',
            'lis_person_name_family'           => 'required',
            'lis_person_name_given'            => 'required',
            'oauth_signature'                  => 'required',
        ];
    }
}