<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function tournaments()
    {
        return $this->hasMany(GroupTournament::class);
    }

    public function events()
    {
        return $this->hasMany(GroupEvent::class);
    }
}
