<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmploymentResource extends JsonResource
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
            "id" => $this->id,
            "employment_status" => $this->employment_status,
            "employment_type" => $this->employment_type,
            "employer" => $this->employer,
            "salary" => number_format($this->salary, 4),
            "salary_text" => $this->salary,
            "status" => $this->status,
            "approval_status" => $this->approval_status,
            "proof_of_employment" => asset('/storage/'.$this->proof_of_employment)
        ];
    }
}
