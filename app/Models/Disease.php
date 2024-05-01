<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $rows = [
        [
            'id' => 1,
            'name' => 'Anorexia Nervosa',
        ],
        [

            'id' => 2,
            'name' => 'Bulimia Nervosa',
        ],
        [

            'id' => 3,
            'name' => 'Binge Eating Disorder',
        ],
        [

            'id' => 4,
            'name' => 'ARFID',
        ],

    ];
}
