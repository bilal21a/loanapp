<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'withdrawal_day' => $this->withdrawal_day,
            'autosave' => $this->autosave,
            'loans' => $this->loans,
            'user_loans' => $this->user_loans,
            'user_investments' => $this->user_investments,
            'investments' => $this->investments,
            'transactions' => $this->transactions,
            'referees' => $this->referees,
            'points' => $this->points,
            'tickets' => $this->tickets,

        ];
    }
}
