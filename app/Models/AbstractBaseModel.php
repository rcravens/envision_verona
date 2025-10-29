<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractBaseModel extends Model
{
    protected $hidden = [ 'created_at', 'updated_at' ];
}
