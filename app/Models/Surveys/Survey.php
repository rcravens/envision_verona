<?php

namespace App\Models\Surveys;

use App\Enums\SurveyStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author_id',
        'survey_blueprint_id',
        'title',
        'description',
        'status',
        'status_at',
        'hash'
    ];

    protected $casts = [
        'status' => SurveyStatus::class,
    ];

    public function author()
    {
        return $this->belongsTo( User::class, 'author_id' );
    }

    public function blueprint()
    {
        return $this->belongsTo( SurveyBlueprint::class, 'survey_blueprint_id' );
    }

    public function sections()
    {
        return $this->hasMany( SurveySection::class );
    }

    public function targets()
    {
        return $this->hasMany( SurveyTarget::class );
    }
}
