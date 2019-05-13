<?php

namespace App\Http\Resources;

use App\CardParticipant;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $participants = [];

        /** @var CardParticipant $participant */
        foreach($this->participants as $participant) {
            $participants[] = $participant->user;
        }

        // TODO: $this->participants->each->user doesn't work

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'creator' => new UserResource($this->creator),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'tasks' => TaskResource::collection($this->tasks),
            'participants' => UserResource::collection(collect($participants)),
        ];
    }
}
