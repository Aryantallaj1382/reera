<?php

namespace App\Http\Controllers\Api\ads\Recruitment;

use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\Language;
use App\Models\RecruitmentAd;
use App\Models\RecruitmentCategory;
use App\Models\User;
use App\Models\VisaType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RecruitmentController
{
    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('slug', 'visa')->with('children')->first();
        if (!$mainCategory) {
            return api_response([], 'دسته‌بندی اصلی پیدا نشد', false);
        }
        $mainChildren = $mainCategory->children->map(function ($child) {
            return [
                'id' => $child->id,
                'category' => $child->title,
            ];
        });
        $extraChildren = [];
        if ($request->filled('category_id')) {
            $selectedCategory = Category::where('id', $request->category_id)->with('children')->first();

            if ($selectedCategory) {
                $extraChildren = $selectedCategory->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'category' => $child->title,
                    ];
                });
            }
        }

        $lang = Ad::whereRelation('category', 'slug', 'visa')->with('address')->get();
        $loc = $lang->filter(fn($item) => $item->address)->map(function ($item) {
            return [
                'latitude' => $item->address->latitude,
                'longitude' => $item->address->longitude,
            ];
        })->values();

        $a = RecruitmentCategory::all();
        $l = Language::all();
        $minPrice = RecruitmentAd::min('price');
        $maxPrice = RecruitmentAd::max('price');

        return api_response([
            'main_category' => $mainChildren,
            'selected_category' => $extraChildren,
            'category' => $a,
            'language' => $l,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'loc' =>$loc
        ]);
    }

    public function show($id)
    {

        $ad = Ad::with('recruitmentAd')->find($id);

        if (!$ad->recruitmentAd) {
            return api_response([], 'wrong id');
        }
        if (auth()->user()) {
            $resume_file = auth()->user()?->info?->resume_file;
            $ResumeCompletion = auth()->user()->resume_completion;
        }

        $return = [
            'id' => $ad->id,
            'is_like' => $ad->is_like,
            'user_id' => $ad->user_id,

            'title' => $ad->title,
            'slug' => $ad->slug,
            'image' => getImages($ad->id),
            'address' => getAddress($ad->id),
            'seller' => getSeller($ad->id),
            'price' => $ad->recruitmentAd?->price,
            'resume_file' => $resume_file ?? null,
            'resume_completion' => $ResumeCompletion ?? null,
            'language' => $ad->recruitmentAd?->language->title,
            'general' => [
                'category' => $ad->recruitmentAd?->category?->name,
                'languages_id' => $ad->recruitmentAd?->languages_id,
                'recruitment_categories_id' => $ad->recruitmentAd?->recruitment_categories_id,
                'days' => $ad->recruitmentAd?->days,
                'time' => $ad->recruitmentAd?->time,
                'work_type' => $ad->recruitmentAd?->type,
                'degree' => $ad->recruitmentAd?->degree,
                'text' => $ad->recruitmentAd->text,
            ],
            'role' => $ad->recruitmentAd->role,
            'skill' => $ad->recruitmentAd->skill,
            'details' => $ad->recruitmentAd->details,
            'category' => $ad->category->title,
            'category_parent' => $ad->root_category_title,
            'check' => $ad->recruitmentAd->check,
            'installments' => $ad->recruitmentAd->installments,
            'cash' => $ad->recruitmentAd->cash,
            'currency_code' => $ad->recruitmentAd?->currency?->code,
            'currency' => $ad->recruitmentAd->currency?->title,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'contact' => [
                'site_massage' => $ad->recruitmentAd->site_massage,
                'my_phone' => $ad->recruitmentAd->my_phone,
                'mobile' => $ad->recruitmentAd->mobile,
            ],


        ];
        return api_response($return);

    }
    public function resume()
    {
//        $user = auth()->user();
        $user =\App\Models\User::first();
        if (!$user) {
            return api_response([], 'wrong user');
        }
        $languages = $user->userLanguages()->with('language')->get();
        $works = $user->workExperiences()->get();
        $educations = $user->educations()->get();
        $skills = $user->skills()->get();
        return view('recruitment.resume', compact(['user' , 'languages' , 'works', 'educations' , 'skills']));

    }
    public function downloadPdf()
    {
        // $user = auth()->user();
        $user = User::first();

        $languages = $user->userLanguages()->with('language')->get();
        $works = $user->workExperiences()->get();
        $educations = $user->educations()->get();
        $skills = $user->skills()->get();

        $pdf = Pdf::loadView('recruitment.pdf', compact(
            'user', 'works', 'educations', 'languages', 'skills'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('resume-' . $user->name . '.pdf');
    }
}
