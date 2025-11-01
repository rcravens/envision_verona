<?php

namespace App\Models\Surveys;

use App\Enums\SurveyQuestionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'survey_section_id',
        'parent_id',
        'order',
        'parent_answers',
        'question',
        'details',
        'additional_info_url',
        'type',
        'allowed_values',
        'is_answer_required',
    ];

    protected $casts = [
        'parent_answers'     => 'array',
        'allowed_values'     => 'array',
        'is_answer_required' => 'boolean',
        'type'               => SurveyQuestionType::class,
    ];

    public function section()
    {
        return $this->belongsTo( SurveySection::class );
    }

    public function parent()
    {
        return $this->belongsTo( self::class, 'parent_id' );
    }

    public function children()
    {
        return $this->hasMany( self::class, 'parent_id' );
    }

    public function answers()
    {
        return $this->hasMany( SurveyAnswer::class );
    }
}
