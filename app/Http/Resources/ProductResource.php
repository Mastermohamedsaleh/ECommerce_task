<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
 
      return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'description'    => $this->description,
            'price'          => (float) $this->price,
            'stock'          => $this->stock,
            'image_url'      => $this->image ? asset('storage/' . $this->image) : null,
            'average_rating' => (float) ($this->average_rating ?? 0),
            'total_reviews'  => (int) ($this->total_reviews ?? 0),
            'created_at'     => $this->created_at?->format('Y-m-d H:i'),
            'category'       => new CategoryResource($this->whenLoaded('category')),
        ];


        
    }
}
