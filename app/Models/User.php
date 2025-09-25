<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Morilog\Jalali\Jalalian;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable ,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|User
     */

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function userLanguages()
    {
        return $this->hasMany(UserLanguage::class);
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class);
    }
    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function info()
    {
        return $this->hasOne(UserInfo::class);
    }

    public function finances()
    {
        return $this->hasMany(Finance::class);
    }

    public function getMembershipDurationAttribute()
    {
        $locale = app()->getLocale();
        $created = $this->created_at;

        switch ($locale) {
            case 'fa':
                return 'عضو شده از ' . Jalalian::fromDateTime($created)->ago();
            case 'ar':
                return __('messages.membership_duration_ar', [
                    'time' => Carbon::parse($created)->diffForHumans(null, true, false, 2)
                ]);
            case 'en':
            default:
                return __('messages.membership_duration_en', [
                    'time' => Carbon::parse($created)->diffForHumans(null, true, false, 2)
                ]);
        }
    }
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }
    public function attributes()
    {
        return $this->hasMany(UserAttribute::class);
    }

}
