<?php

namespace App\Models\Surveys;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyTarget extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'survey_id',
        'user_id',
        'hash',
        'first_name',
        'last_name',
        'email',
        'is_completed',
        'last_viewed_at'
    ];

    protected $casts = [
        'is_completed'   => 'boolean',
        'last_viewed_at' => 'datetime',
    ];

    public function survey()
    {
        return $this->belongsTo( Survey::class );
    }

    public function user()
    {
        return $this->belongsTo( User::class );
    }

    public function answers()
    {
        return $this->hasMany( SurveyAnswer::class );
    }
}
