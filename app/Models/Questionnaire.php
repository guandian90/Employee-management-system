<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
