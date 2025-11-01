<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyTemplateRequest extends FormRequest
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
                    'title' => 'required',
                ];
            case 'PUT':
            {
                return [
                    'title' => 'required',
                ];
            }
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
