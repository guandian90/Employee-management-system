<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Step extends Model
{
    public function questionnaire()
    {
        return $this->hasOne(Questionnaire::class);
    }

    public function progressRecords()
    {
        return $this->hasMany(UserStepProgress::class);
    }
    public function userStepProgress()
    {
        return $this->hasOne(UserStepProgress::class, 'step_id', 'id')->where('user_id', Auth::id());
    }
}
