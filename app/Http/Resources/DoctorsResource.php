<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => 1,
            "name" => $this->name,
            "job" => $this->job,
            "img" => $this->img,
            "description" => $this->description,
        ];
    }
}
