<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,

            // بیرون از meta برمی‌گردونیم
            'current_page' => $this->currentPage(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
            'total' => $this->total(),

        ];
    }

}
