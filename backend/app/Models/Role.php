<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    public function privileges(): BelongsToMany
    {
        return $this->belongsToMany(Privilege::class, 'privilege_role');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
