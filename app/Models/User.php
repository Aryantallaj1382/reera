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
    public function getResumeCompletionAttribute()
    {
        $totalSections = 5;
        $completedSections = 0;

        // 1️⃣ اطلاعات پایه
        if ($this->name && $this->age && $this->mobile && $this->image) {
            $completedSections++;
        }

        // 2️⃣ زبان‌ها
        if ($this->userLanguages()->exists()) {
            $completedSections++;
        }

        // 3️⃣ سوابق کاری
        if ($this->workExperiences()->exists()) {
            $completedSections++;
        }

        // 4️⃣ تحصیلات
        if ($this->educations()->exists()) {
            $completedSections++;
        }

        // 5️⃣ مهارت‌ها
        if ($this->skills()->exists()) {
            $completedSections++;
        }

        return round(($completedSections / $totalSections) * 100);
    }

    public function finances()
    {
        return $this->hasMany(Finance::class);
    }
    public function getProfileAttribute($value)
    {
        return $value? url('public/'.$value) : null;

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

    public function nationalty()
    {
        return $this->belongsTo(Nationality::class);
    }
    public function getIsIranAttribute()
    {
        return $this?->nationality?->id == 1 ? true : false;

    }
    public function adComments()
    {
        return $this->hasManyThrough(
            Comment::class,  // مدل مقصد
            Ad::class,       // مدل میانی
            'user_id',       // کلید در جدول ads (user_id)
            'commentable_id',// کلید در جدول comments
            'id',            // کلید در جدول users
            'id'             // کلید در جدول ads
        )->where('commentable_type', Ad::class);
    }
    public function getAverageOwnerBehaviorRatingAttribute()
    {
        return round($this->adComments()->avg('owner_behavior_rating'), 1);
    }

    public function getAveragePriceClarityRatingAttribute()
    {
        return round($this->adComments()->avg('price_clarity_rating'), 1);
    }

    public function getAverageInfoHonestyRatingAttribute()
    {
        return round($this->adComments()->avg('info_honesty_rating'), 1);
    }

    public function getAverageCleanlinessRatingAttribute()
    {
        return round($this->adComments()->avg('cleanliness_rating'), 1);
    }
    public function getRatingsSummaryAttribute()
    {
        $comments = $this->adComments();

        // محاسبه میانگین هر فیلد
        $owner_behavior = round($comments->avg('owner_behavior_rating'), 1);
        $price_clarity  = round($comments->avg('price_clarity_rating'), 1);
        $info_honesty   = round($comments->avg('info_honesty_rating'), 1);
        $cleanliness    = round($comments->avg('cleanliness_rating'), 1);

        // محاسبه میانگین کلی (از ۴ فیلد)
        $allRatings = array_filter([$owner_behavior, $price_clarity, $info_honesty, $cleanliness]);
        $overall = !empty($allRatings) ? round(array_sum($allRatings) / count($allRatings), 1) : 0;

        return $overall;
    }
    public function likedAds()
    {
        return $this->morphedByMany(Ad::class, 'likeable', 'likes')
            ->withTimestamps();
    }
    public function getNameAttribute()
    {
        return $this->first_name . ' ' .$this->last_name ;

    }


}
