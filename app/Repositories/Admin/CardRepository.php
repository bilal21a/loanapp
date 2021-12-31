<?php 
namespace App\Repositories\Admin;

use App\Card;
use App\Profile;
use App\Http\Resources\CardResource;

class CardRepository {
    
    protected $card;
    protected $profile;

    public function __construct(Card $card, Profile $profile) {
        $this->card = $card;
        $this->profile = $profile;
    }

    public function index() {
        $data = $this->card->orderBy("created_at", "desc")->paginate(5);
        return CardResource::collection($data);
    }

    public function create($id) {
        $uid = $this->profile->findOrFail($id)->id;
        return view("website.admin.card.new-card")->withUid($uid);
    }

    public function addCard($request) {
        $user = $this->profile->find($request->uid);

        $card = (object) array(
          "email" => $user->email,
          "amount" => 50,
          "desc" => "Card verification",
          "cardcvv"    => $request->cardcvv,
          "cardNumber"    => $request->cardNumber,
          "expiryMonth" => $request->expiryMonth,
          "expiryYear" => $request->expiryYear,
          "cardPin" => $request->cardPin,
        );
      $response = \Payment::charge($card);
      if(!$response["status"]) {
        // return back()->withErrors($response["message"]);
        return back()->withErrors("A network error occured. Check your internet connection");
      }

      if($response["status"] && $response["data"]["authorization"]["reusable"]) {

            $newcard = $this->card->create([
                "user_profile_id" => $user->id,
                "card_type" => $response["data"]["authorization"]["card_type"],
                "card_number" => $response["data"]['authorization']["last4"],
                "expiry" => $response["data"]['authorization']["exp_year"]."/".$response["data"]['authorization']["exp_month"],
                "auth_code" => $response["data"]["authorization"]["authorization_code"],
                "bank_name" => $response["data"]['authorization']["bank"],
            ]);

            if($newcard) {
                return back()->withMessage("Card added successfully");
            }

            return back()->withErrors("An error occured adding card. Try again");
      } else {
        return back()->withErrors("Your card could not be charged");
      }
      
        return back()->withErrors("A error occured.");
    }

    public function getCard($id) {
        $card = $this->card->find($id);
        if($card) {
            return view("website.admin.cards.index")->withCard(new CardResource($card));
        }

        return back()->withErrors("No matching recird found");
    }

    public function delete($id) {
        $card = $this->card->find($id);
        if($card) {
            $card->delete();
            return back()->withMessage("Card removed");
        }
        return back()->withErrors("Error deleting card.");
    }
}