<?php

namespace App\Http\Controllers\Api\ads\Ticket;

use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\Ticket;
use App\Models\TicketAd;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketController
{

    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('slug', 'ticket')->with('children')->first();
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

        $lang = Ad::whereRelation('category', 'slug', 'ticket')->with('address')->get();
        $a = $lang->filter(fn($item) => $item->address)->map(function ($item) {
            return [
                'latitude' => $item->address->latitude,
                'longitude' => $item->address->longitude,
            ];
        })->values();
        $brand = TicketType::all();
        $minPrice =TicketAd::min('price');
        $maxPrice =TicketAd::max('price');

        return api_response([
            'main_category' => $mainChildren,
            'selected_category' => $extraChildren,
            'type' => $brand,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'loc' =>$a
        ]);
    }


    public function show($id)
{

    $ad = Ad::with('ticket')->find($id);
    $ad->increment('view');

    if(!$ad->ticket)
    {
        return api_response([], 'wrong id');
    }
    $return =[

        'id' => $ad->id,
        'title' => $ad->title,
        'slug' => $ad->slug,
        'image' => getImages($ad->id),
        'address' => getAddress($ad->id),
        'seller' => getSeller($ad->id),
        'is_like' => $ad->is_like,
        'user_id' => $ad->user_id,

        'category' => $ad->category->title,
        'category_parent' => $ad->root_category_title,
        'price' => $ad->ticket->price,
        'date' => $ad->ticket->date,
        'number' => $ad->ticket->number,
        'ticketType' => $ad->ticket->ticketType?->name,
        'donation' => $ad->ticket->donation,
        'check' => $ad->ticket->check,
        'installments' => $ad->ticket->installments,
        'cash' => $ad->ticket->cash,
        'currency_code' => $ad->ticket?->currency?->code,
        'currency' => $ad->ticket->currency?->title,
        'location' => $ad->location,
        'time' => $ad->time_ago,
        'membership' => $ad->user->membership_duration,
        'text' => $ad->ticket->text,
        'contact' => [
            'site_massage' => $ad->ticket->site_massage,
            'my_phone' => $ad->ticket->my_phone,
            'mobile' => $ad->ticket->mobile,
        ],



    ];
    return api_response($return);

}


}
