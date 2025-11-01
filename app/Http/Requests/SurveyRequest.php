<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
{
    public function authorize()
    {
        switch ( $this->method() )
        {
            case 'PUT':
            case 'POST':
            case 'DELETE':
            case 'PATCH':
            {
                if ( true )
                {
                    return true;
                }
                break;
            }
            case 'GET':
            default:
            {
                return true;
            }
        }

        return false;
    }

    public function rules()
    {
        switch ( $this->method() )
        {
            case 'POST':
                return [
                    'user_id' => 'required|exists:users,id'
                ];
            case 'PUT':
            case 'GET':
            case 'DELETE':
            case 'PATCH':
            default:
            {
                return [];
            }
        }
    }
}
