<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetHolderHistory extends Model
{
    protected $table = 'asset_holder_history';

    protected $fillable = [
        'asset_id',
        'previous_holder',
        'new_holder',
        'changed_at',
        'notes',
        'changed_by_user_id',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function changedByUser()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
