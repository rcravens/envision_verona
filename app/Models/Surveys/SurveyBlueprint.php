<?php

namespace App\Models\Surveys;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyBlueprint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author_id',
        'title',
        'description',
        'is_active',
        'tags'
    ];

    protected $casts = [
        'tags'      => 'array',
        'is_active' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo( User::class, 'author_id' );
    }

    public function sections()
    {
        return $this->hasMany( SurveyBlueprintSection::class );
    }
}
