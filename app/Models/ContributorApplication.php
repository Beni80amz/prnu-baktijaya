<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributorApplication extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'experience',
        'bio',
        'status',
        'note',
        'reviewed_at',
        'reviewed_by',
    ];

    protected static function booted()
    {
        static::created(function ($application) {
            $admins = User::role(['super_admin', 'admin_konten'])->get();

            \Filament\Notifications\Notification::make()
                ->info()
                ->title('Pendaftaran Kontributor Baru')
                ->body("{$application->name} mendaftar sebagai kontributor.")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->button()
                        ->url(fn() => \App\Filament\Resources\ContributorApplicationResource::getUrl('index')),
                ])
                ->sendToDatabase($admins);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
