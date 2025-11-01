<?php

namespace App\Enums;

enum SurveyQuestionType: string
{
    case Text = 'text';
    case TextPlus = 'text_plus';
    case Date = 'date';
    case Email = 'email';
    case Url = 'url';
    case Boolean = 'boolean';
    case Audit = 'audit';
    case DropDown = 'drop_down';
    case Checkboxes = 'checkboxes';
    case File = 'file';
    case Photo = 'photo';
}
