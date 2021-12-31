<?php 
namespace App\Services\Admin;

use App\Repositories\Admin\CardRepository;

class CardService {
    
    protected $cardRepo;

    public function __construct(CardRepository $cardRepo) {
        $this->cardRepo = $cardRepo;
    }

    public function index() {
        return $this->cardRepo->index();
    }

    public function create($id) {
        return $this->cardRepo->create($id);
    }

    public function newCard($request) {
        return $this->cardRepo->addCard($request);
    }

    public function find($id) {
        return $this->cardRepo->getCard($id);
    }

    public function update($id, $request) {
        return $this->cardRepo->update($id, $request);
    }

    public function delete($id) {
        return $this->cardRepo->delete($id);
    }
}