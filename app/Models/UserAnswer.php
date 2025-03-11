<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    // 表名（默认为 user_answers）
    protected $table = 'user_answers';

    // 可批量赋值的字段
    protected $fillable = [
        'user_id',
        'question_id',
        'answer',
    ];

    // 时间戳配置（默认开启 created_at 和 updated_at）
    public $timestamps = true;

    /**
     * 关联到 User 模型
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 关联到 Question 模型（假设已存在）
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
