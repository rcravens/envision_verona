<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyAnswer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'survey_target_id',
        'survey_question_id',
        'answered_at',
        'answer'
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    public function target()
    {
        return $this->belongsTo( SurveyTarget::class, 'survey_target_id' );
    }

    public function question()
    {
        return $this->belongsTo( SurveyQuestion::class, 'survey_question_id' );
    }
}
