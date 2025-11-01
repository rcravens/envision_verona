<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveySection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'survey_id',
        'order',
        'title',
        'description'
    ];

    public function survey()
    {
        return $this->belongsTo( Survey::class );
    }

    public function questions()
    {
        return $this->hasMany( SurveyQuestion::class );
    }
}
