<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\Language;
use App\Models\Nationality;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function info()
    {
        $n = Nationality::all();
        $l = Language::all();
        return api_response([
            'nationalities' => $n,
            'languages' => $l,
        ]);
    }
    public function profile(Request $request)
    {
        $user = auth()->user();

        return api_response([
            'profile' => $user->profile,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'mobile' => $user->mobile,
            'national_code' => $user->national_code,
            'identity_document' => $user->identity_document,
            'language' => $user->language->title ?? null,
            'nationality' => $user->nationality->title ?? null,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|unique:users,mobile,' . $user->id,
            'national_code' => 'nullable|string|max:10',
            'language_id' => 'nullable|exists:languages,id',
            'nationality_id' => 'nullable|exists:nationalities,id',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'identity_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($request->hasFile('profile')) {
            if ($user->profile) {
                Storage::delete($user->profile);
            }

            $validated['profile'] = $request->file('profile')->store('users/profile');
        }

        if ($request->hasFile('identity_document')) {
            if ($user->identity_document) {
                Storage::delete($user->identity_document);
            }

            $validated['identity_document'] = $request->file('identity_document')->store('users/documents');
        }

        $user->update($validated);

        return api_response( [], __('messages.saved_successfully'));

    }



    public function getUserAttributes(Request $request)
    {
        $user = auth()->user();

        $attributes = $user->attributes()->select(['id','value'])->get();

        return api_response([
            'attributes' => $attributes,
        ]);
    }
    public function updateUserAttributes(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'attributes' => 'required|array',
            'attributes.*' => 'nullable|string',
        ]);

        // حذف کل ویژگی‌ها قبلی
        $user->attributes()->delete();

        // ذخیره مقادیر جدید (value فقط)
        foreach ($data['attributes'] as $value) {
            $user->attributes()->create([
                'value' => $value,
            ]);
        }

        return api_response( [], __('messages.saved_successfully'));

    }


    public function finance(Request $request)
    {
        $user = $request->user();

        $finances = Finance::where('user_id', $user->id)->get();

        return api_response([
            'finances' => $finances,
        ]);
    }


    public function storeFinance(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'card_number' => 'required|string|max:255',
        ]);

        $finance = Finance::create([
            'user_id' => $user->id,
            'card_number' => $validated['card_number'],
            'status' => 'pending',
        ]);

        return api_response( [], __('messages.saved_successfully'));
    }
    public function updateFinance(Request $request, $id)
    {
        $user = $request->user();

        $finance = Finance::where('user_id', $user->id)->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'card_number' => 'required|string|max:255',
        ]);

        $finance->update([
            'card_number' => $validated['card_number'],
        ]);

        return api_response( [], __('messages.saved_successfully'));

    }
    public function destroyFinance(Request $request, $id)
    {
        $user = $request->user();

        $finance = Finance::where('user_id', $user->id)->where('id', $id)->firstOrFail();

        $finance->delete();

        return api_response([]);
    }


    public function storeIntroVideo(Request $request)
    {
        $request->validate([
            'intro_video' => 'required|file|mimes:mp4,mov,avi,webm|max:51200',
        ]);

        $user = auth()->user();

        $info = $user->info;

        if ($info && $info->intro_video && \Storage::disk('public')->exists($info->intro_video)) {
            \Storage::disk('public')->delete($info->intro_video);
        }

        $path = $request->file('intro_video')->store('intro_videos', 'public');

        $userInfo = UserInfo::updateOrCreate(
            ['user_id' => $user->id],
            ['intro_video' => $path]
        );

        return api_response();
    }
    public function showIntroVideo()
    {
        $user = auth()->user();

        $info = $user->info;

        if (!$info || !$info->intro_video) {
            return api_response(['has_video' => false], 'ویدیویی وجود ندارد.',200);
        }

        return api_response([
            'has_video' => false,
            'video' => $info->intro_video,
        ], __('messages.saved_successfully'));

    }





    public function updateLanguages(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'languages' => 'required|array',
            'languages.*.language_id' => 'required|exists:languages,id',
            'languages.*.level' => 'required|in:basic,intermediate,advanced,native',
        ]);

        $user->userLanguages()->delete();

        foreach ($data['languages'] as $lang) {
            $user->userLanguages()->create([
                'language_id' => $lang['language_id'],
                'level' => $lang['level'],
            ]);
        }

        return api_response( [], __('messages.saved_successfully'));

    }


    public function getLanguages(Request $request)
    {
        $user = $request->user();

        $languages = $user->userLanguages()->with('language')->get()->map(function ($language) {
            return [
                'id' => $language->language->id,
                'name' => $language->language->title,
                'level' => $language->level,

            ];
        });

        return api_response([
            'languages' => $languages,
        ]);
    }


    public function getResidencyStatus(Request $request)
    {
        $user = $request->user();
        $info = $user->info;  // رابطه یک به یک UserInfo

        if (!$info) {
            return api_response([
                'message' => 'اطلاعات کاربر یافت نشد.'
            ], 404);
        }

        return api_response([
            'residency_status' => $info->residency_status,
        ]);
    }
    public function updateResidencyStatus(Request $request)
    {
        $request->validate([
            'residency_status' => 'required|in:permanent,student,asylum_seeker,temporary,other',
        ]);

        $user = $request->user();

        $info = $user->info;

        if (!$info) {
            // اگر رکورد user_info نیست، ایجاد می‌کنیم
            $info = $user->info()->create([
                'residency_status' => $request->residency_status,
            ]);
        } else {
            // اگر هست، بروزرسانی می‌کنیم
            $info->update([
                'residency_status' => $request->residency_status,
            ]);
        }

        return api_response( [], __('messages.saved_successfully'));

    }



    public function getSalaryRange(Request $request)
    {
        $user = $request->user();
        $info = $user->info;

        if (!$info) {
            return api_response([
                'message' => 'اطلاعات کاربر یافت نشد.'
            ], 404);
        }

        return api_response([
            'min_salary' => $info->min_salary,
            'max_salary' => $info->max_salary,
        ]);
    }



    public function updateSalaryRange(Request $request)
    {
        $request->validate([
            'min_salary' => 'required|integer|min:0',
            'max_salary' => 'required|integer|min:0|gte:min_salary',
        ]);

        $user = $request->user();

        $info = $user->info;

        if (!$info) {
            $info = $user->info()->create([
                'min_salary' => $request->min_salary,
                'max_salary' => $request->max_salary,
            ]);
        } else {
            $info->update([
                'min_salary' => $request->min_salary,
                'max_salary' => $request->max_salary,
            ]);
        }

        return api_response( [], __('messages.saved_successfully'));

    }

    public function store_resume_file(Request $request)
    {
        $request->validate([
            'resume_file' => 'required|file|max:51200',
        ]);

        $user = auth()->user();

        $info = $user->info;

        if ($info && $info->resume_file && \Storage::disk('public')->exists($info->resume_file)) {
            \Storage::disk('public')->delete($info->resume_file);
        }

        $path = $request->file('resume_file')->store('resume_files', 'public');

        $userInfo = UserInfo::updateOrCreate(
            ['user_id' => $user->id],
            ['resume_file' => $path]
        );

        return api_response( [], __('messages.saved_successfully'));
    }
    public function show_resume_file()
    {
        $user = auth()->user();

        $info = $user->info;

        if (!$info || !$info->resume_file) {
            return api_response(['message' => 'رزومه وجود ندارد.'], 404);
        }

        return api_response([
            'video_url' => asset('storage/' . $info->resume_file),
        ]);
    }



    public function updateWorkExperiences(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'work_experiences' => 'required|array',
            'work_experiences.*.title' => 'required|string|max:255',
            'work_experiences.*.company_name' => 'required|string|max:255',
            'work_experiences.*.start_month' => 'required|integer|between:1,12',
            'work_experiences.*.start_year' => 'required|integer|min:1900',
            'work_experiences.*.end_month' => 'nullable|integer|between:1,12',
            'work_experiences.*.end_year' => 'nullable|integer|min:1900',
            'work_experiences.*.is_current' => 'required|boolean',
            'work_experiences.*.description' => 'nullable|string',
        ]);

        $user->workExperiences()->delete();

        // ذخیره سوابق جدید
        foreach ($data['work_experiences'] as $work) {
            $user->workExperiences()->create($work);
        }
        return api_response( [], __('messages.saved_successfully'));

    }

    public function getWorkExperiences(Request $request)
    {
        $user = $request->user();

        $workExperiences = $user->workExperiences()->orderBy('start_year', 'desc')->orderBy('start_month', 'desc')->get();

        return api_response([
            'work_experiences' => $workExperiences,
        ]);
    }

    public function updateEducations(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'educations' => 'required|array',
            'educations.*.major' => 'required|string|max:255',
            'educations.*.university_name' => 'required|string|max:255',
            'educations.*.degree' => 'nullable|in:diploma,associate,bachelor,master,phd',
            'educations.*.start_year' => 'required|integer|min:1900',
            'educations.*.end_year' => 'nullable|integer|min:1900',
            'educations.*.is_current' => 'required|boolean',
            'educations.*.description' => 'nullable|string',
        ]);

        // حذف همه سوابق قبلی تحصیلی
        $user->educations()->delete();

        // ذخیره سوابق جدید
        foreach ($data['educations'] as $education) {
            $user->educations()->create($education);
        }

        return api_response( [], __('messages.saved_successfully'));

    }
    public function getEducations(Request $request)
    {
        $user = $request->user();

        $educations = $user->educations()->orderBy('start_year', 'desc')->get();

        return api_response([
            'educations' => $educations,
        ]);
    }

    public function updateSkills(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'skills' => 'required|array',
            'skills.*.name' => 'required|string|max:255',
        ]);

        // حذف همه مهارت‌های قبلی
        $user->skills()->delete();

        // ذخیره مهارت‌های جدید
        foreach ($data['skills'] as $skill) {
            $user->skills()->create([
                'name' => $skill['name'],
            ]);
        }

        return api_response( [], __('messages.saved_successfully'));

    }
    public function getSkills(Request $request)
    {
        $user = $request->user();

        $skills = $user->skills()->select(['id','name'])->get();

        return api_response([
            'skills' => $skills,
        ]);
    }



}
