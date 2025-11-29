<?php

namespace App\Http\Controllers\Api\ads\Ticket;

use App\Models\Ad;

class TicketController
{
    public function show($id)
{

    $ad = Ad::with('ticket')->find($id);

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
