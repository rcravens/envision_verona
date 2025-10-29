<?php

namespace App\Models;

class HousingPrice extends AbstractBaseModel
{
    protected $table = 'housing_prices';

    protected $casts = [
        'date' => 'date',
    ];
}
