<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "gender" => $this->gender,
            "email" => $this->email,
            "verified_at" => (string) $this->email_verified_at,
            "phone_number" => $this->phone_number,
            "created_at" => (string) $this->created_at,
            "updated_at" => (string) $this->updated_at,
            "kyc" => new KycResource($this->kyc),
            "kycs" => KycsResource::collection($this->kycs),
            "points" => $this->kycs()->sum("points") + (!empty($this->agentApproval) ? $this->agentApproval->points : 0) + (!empty($this->employment) ? $this->employment->points : 0) + (count($this->social_links) > 0 ? $this->social_links()->first()->points : 0),
            "account" => AccountResource::collection($this->accounts),
            "banks" => BankResource::collection($this->banks),
            "cards" => CardResource::collection($this->cards),
            "investments" => InvestmentResource::collection($this->investments),
            "loans" => LoanResource::collection($this->loans),
            "transactions" => TransactionResource::collection($this->transactions),
            "kin" => new NextOfKinResource($this->nextOfKin),
            "user_type" => $this->user_type,
            "agent_approval" => $this->agentApproval,
            "approvals" => $this->user_type === 'agent' ? $this->approvals : null,
            "social_links" => $this->social_links,
//            "employment_status" => $this->employment
            "employment_status" => new EmploymentResource($this->employment),
            "wallet_balance" => $this->accounts[0]["current_balance"],
            "savings_balance" => $this->accounts[1]["current_balance"],
            "networth" => $this->accounts[0]["current_balance"] + $this->accounts[1]["current_balance"] +
                $this->investments()->where("status", 1)->sum("amount"),
            "referees" => $this->referees,
            "referer" => $this->referer,
            "affiliates" => AffiliateResource::collection($this->affiliates),
            "app_settings" => $this->app_settings,
            "tickets" => TicketResource::collection($this->tickets)
        ];
    }
}
