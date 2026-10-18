<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataArsip extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'document_path',
        'category_id',
        'status',
    ];

    protected $appends = [
        'document_name',
    ];

    public function getDocumentNameAttribute(): string
    {
        return $this->title ?: basename($this->document_path);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
