<?php

namespace App\Http\Controllers\Api\ads;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\AdReport;
use App\Models\Category\Category;
use App\Models\Chat;
use App\Models\City;
use App\Models\Comment;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Kitchen\KitchenBrand;
use App\Models\Kitchen\KitchenType;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{


    public function index(Request $request)
    {
        $query = Ad::query();
        $currency = $request->currency;
        $query->filterCommon($request);
        if ($request->category_slug == 'housing') {
            $query->filterHousing($request);
        }
        elseif ($request->category_slug == 'vehicles') {
            $query->vehicles($request);
        }
        elseif ($request->category_slug == 'digital') {
            $query->digital($request);
        }
        elseif ($request->category_slug == 'recruitment') {
            $query->recruitment($request);
        }
        elseif ($request->category_slug == 'kitchen') {
            $query->kitchen($request);
        }
        elseif ($request->category_slug == 'ticket') {
            $query->ticket($request);
        }
        elseif ($request->category_slug == 'business') {
            $query->business($request);
        }
        elseif ($request->category_slug == 'personal') {
            $query->personal($request);
        }
        elseif ($request->category_slug == 'visa') {
            $query->visa($request);
        }
        elseif ($request->category_slug == 'trip') {
            $query->trip($request);
        }
        elseif ($request->category_slug == 'housemate') {
            $query->housemate($request);
        }
        $ads = $query->latest()->paginate();

        $ads->getCollection()->transform(function ($ad) {
            return [
                'id'          => $ad->id,
                'title'       => $ad->title,
                'time'        => $ad->time_ago,
                'image'       => $ad->image,
                'is_verified' => $ad->is_verified,
                'location'    => $ad->location,
                'category'    => $ad->category->title,
                'custom_info' => $ad->custom_info,
                'root_category_slug' => $ad->root_category_slug,
                'price' => $ad->price,
                'currency' => $ad?->currency?->title,
            ];
        });

        return api_response($ads);
    }

    public function get_filters(Request $request)
    {
        $country_id = $request->input('country_id');
        $category = Category::where('parent_id', null)->get();
        $country = Country::all();
        $city = City::where('country_id', $country_id)->get();
        $currency = Currency::all();
        $min_price = Ad::min('price');
        $max_price = Ad::max('price');

        return api_response([
            'country' => $country,
            'city' => $city,
            'currency' => $currency,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'category' => $category,
        ]);


    }
    public function rates()
    {
        $a = getRates();
        return api_response($a);
    }
    public function toggleLike(Request $request, $id)
    {
        $as = Ad::findOrFail($id);
        $user = auth()->user();

        if (!$user) {
            return api_response([], 'برای لایک کردن لاگین کنید', 401);
        }

        $like = $user->likes()
            ->where('likeable_id', $as->id)
            ->where('likeable_type', Ad::class)
            ->first();

        if ($like) {
            $like->delete();
            return api_response([], 'لایک حذف شد');
        }

        $as->likes()->create([
            'user_id' => $user->id,
        ]);

        return api_response([], 'لایک شد');
    }

    public function convert1(Request $request)
    {

        $a = convert($request->price , $request->from , $request->to );
        return api_response($a);

    }
    public function store(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:191',
        ]);

        $ad = Ad::findOrFail($id);

        AdReport::create([
            'reporter_id' => auth()->id(),
            'ad_id'       => $ad->id,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        return api_response([]);
    }
    public function delete($id)
    {
        $ad = Ad::find($id);

        if (!$ad) {
            return api_response([], __('messages.not_found'), 404);
        }

        foreach ($ad->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }
        $ad->delete();

        return api_response([], __('messages.deleted_successfully'));
    }
    public function request_ad(Request $request , $id)
    {
        $ad = Ad::findOrFail($id);
        $request->validate([
            'text' => 'required',
        ]);
        $chat = Chat::where('user_one_id', auth()->id())->where('ad_id', $id)->first();
        if ($chat) {
            return api_response([], 'شما قبلا برای این درخواست دلده اید' , 422);
        }
        $chat = Chat::create([
            'user_one_id' => auth()->id() ,
            'user_two_id' => $ad->user->id,
            'ad_id'       => $ad->id,
            'status'      => 'pending',

        ]);
        $chat->messages()->create([
           'sender_id' => auth()->id(),
            'message' => $request->text,
        ]);
        return api_response([], 'درخواست ثبت شد');

    }
    public function currency()
    {
        $currency = Currency::all();
        return api_response($currency);

    }


    /**
     * نمایش کامنت‌های تایید شده یک آگهی (Ad) به همراه ریپلای‌ها
     *
     * @param int $adId
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments($id)
    {
        // اگر مدل Ad داری، می‌تونی اول چک کنی وجود داره یا نه
        $ad = Ad::findOrFail($id);

        $comments = Comment::where('commentable_type', \App\Models\Ad::class)
            ->where('commentable_id', $id)
            ->where('status', 'approved')
            ->withCount('likes') // تعداد لایک‌ها
            ->with(['likes' => function ($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                }
            }])
            ->orderBy('created_at', 'desc')
            ->paginate();

        $comments->getCollection()->transform(function ($comment) {
                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'user' => [
                        'name' => $comment->user->name,
                        'profile' => $comment->user->profile ? asset($comment->user->profile) : asset('default-avatar.png'),
                    ],
                    'created_at' => $comment->created_at->diffForHumans(), // مثلاً "2 ساعت پیش"
                    'average_rating' => $comment->average_rating,
                    'likes_count' => $comment->likes_count,
                    'is_liked' => $comment->likes->isNotEmpty(), // آیا کاربر فعلی لایک کرده
                    'replies' => $comment->replies->map(function ($reply) {
                        return [
                            'id' => $reply->id,
                            'body' => $reply->body,
                            'user' => [
                                'name' => $reply->user->name,
                                'profile' => $reply->user->profile ? asset($reply->user->profile) : asset('default-avatar.png'),
                            ],
                            'created_at' => $reply->created_at->diffForHumans(),
                        ];
                    }),
                ];
            });

        return api_response($comments);
    }
}
