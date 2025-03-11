<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function getOptionsAttribute($value)
    {
        return is_null($value) ? [] : json_decode($value, true);
    }

}
