<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kiai extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'is_active',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($kiai) {
            if (!$kiai->user_id) {
                $emailName = strtolower(str_replace(' ', '.', $kiai->name));
                $email = $emailName . '@prnubaktijaya.org';

                // Ensure unique email
                $count = 1;
                while (\App\Models\User::where('email', $email)->exists()) {
                    $email = $emailName . $count . '@prnubaktijaya.org';
                    $count++;
                }

                $user = \App\Models\User::create([
                    'name' => $kiai->name,
                    'email' => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make('kiai12345'),
                    'status' => 'active',
                ]);

                $user->assignRole('kiai');

                $kiai->update(['user_id' => $user->id]);
            }
        });
    }
}
