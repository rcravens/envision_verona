<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyBlueprintSection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'survey_blueprint_id',
        'order',
        'title',
        'description'
    ];

    public function blueprint()
    {
        return $this->belongsTo( SurveyBlueprint::class, 'survey_blueprint_id' );
    }

    public function questions()
    {
        return $this->hasMany( SurveyBlueprintQuestion::class );
    }
}
