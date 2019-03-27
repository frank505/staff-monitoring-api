<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleTaskGeterCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "task_header"=> $this->service_id,
            "users_assigned" => json_decode($this->users_assigned),
            "task_content"=> $this->task_content,
            "attached_file"=> $this->attached_file,
            "created_at"=>$this->created_at,
            "updated_at"=>$this->updated_at
        ];
    }
}
