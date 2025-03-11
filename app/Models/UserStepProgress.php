<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStepProgress extends Model
{
    // 表名（默认为 user_step_progress）
    protected $table = 'user_step_progress';

    // 可填充字段
    protected $fillable = [
        'user_id',
        'step_id',
        'progress_percent',
        'completed_at',
    ];

    // 时间戳配置（如果表中有 created_at/updated_at）
    public $timestamps = true;

    /**
     * 关联到 User 模型
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 关联到 Step 模型
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(Step::class);
    }
}
