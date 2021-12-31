<?php
namespace App\Repositories;

use App\Card;
use App\Http\Resources\CardResource;
use App\Http\Resources\ProfileResource;

class CardRepository {

    protected $card;

    public function __construct(Card $card) {
        $this->card = $card;
    }

    public function index() {
        $data = $this->card->where('user_profile_id', auth()->guard('profile')->user()->id)->orderBy("created_at", "desc")->paginate(5);
        return response()->json(['status' => true, 'data' => CardResource::collection($data)]);
    }

    public function addCard($request) {
        $newcard = $this->card->create([
            "user_profile_id" => auth()->guard("profile")->user()->id,
            "card_type" => $request->cardType,
            "card_number" => $request->number,
            "expiry" => $request->expYear."/".$request->expMonth,
            "auth_code" => $request->authCode,
            "bank_name" => $request->bank,
        ]);

        if($newcard) {
            return new CardResource($newcard);
        }

        return response()->json("An error occured adding card. Try again");
    }

    public function getCard($id) {
        $card = $this->card->find($id);
        if($card) {
            return new CardResource($card);
        }

        return response()->json(["message" => "No matching recird found", "status" => false]);
    }

    public function delete($id) {
        $card = $this->card->find($id);
        if($card) {
            $userCard = $this->card->where("user_profile_id", auth()->guard("profile")->user()->id)->get();
            if(count($userCard) > 1) {
                $card->delete();
                return response()->json(["message" => "Deleted", "status" => true]);
            }
            return response()->json(["message" => "Error deleting card. Cannot remove all cards", "status" => false]);
        }
        return response()->json(["message" => "No record found", "status" => false]);
    }

    public function setCard($id) {

        $card = $this->card->find($id);
        $user = auth()->guard('profile')->user();
        if($card) {

            $userCard = $this->card->find($id);
            $activeCard = $this->card->where("is_default", 1)->where("user_profile_id", $user->id)->first();
            if($activeCard) {
                $activeCard->update(["is_default" => false]);
            }

            $userCard->update(['is_default' => true]);

            if($userCard) {
                return response()->json(["message" => "Your card has been set as default", "status" => true, "user" => new ProfileResource($user)]);
            }

            return response()->json(["message" => "Could not set default", "status" => false]);

        }

        return response()->json(["message" => "No record found", "status" => false]);

    }
}
