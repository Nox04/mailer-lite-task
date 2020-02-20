<?php

namespace App\Http\Resources;

use App\Enums\SubscriberState;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SubscriberResource
 * @property mixed updated_at
 * @property mixed created_at
 * @property SubscriberState state
 * @property string name
 * @property string email
 * @property mixed id
 */
class SubscriberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'state' => $this->state,
            'fields' => FieldResource::collection($this->whenLoaded('fields')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
