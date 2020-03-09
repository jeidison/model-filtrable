<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Jeidison\Filtrable\Filtrable;

class ModelTest extends Model
{
    use Filtrable;

    protected $fillable = [
        'field_one'
    ];
}
