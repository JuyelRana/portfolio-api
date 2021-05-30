<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'username' => $this->username,
            'image' => asset('images/users/' . $this->image),
            'created_time' => [
                'time_difference' => $this->created_at ? $this->created_at->diffForHumans() : '',
                'created_at' => $this->created_at
            ],
            'updated_time' => [
                'time_difference' => $this->updated_at ? $this->updated_at->diffForHumans() : '',
                'created_at' => $this->updated_at
            ],
            'active' => $this->active ? 'Active' : 'In Active',
        ];
    }
}
