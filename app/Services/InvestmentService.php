<?php 
namespace App\Services;

use App\Repositories\InvestmentRepository;

class InvestmentService {
    
    protected $invetsmentRepo;

    public function __construct(InvestmentRepository $invetsmentRepo) {
        $this->invetsmentRepo = $invetsmentRepo;
    }

    public function index() {
        return $this->invetsmentRepo->index();
    }

    public function store($request) {
        return $this->invetsmentRepo->store($request);
    }

    public function show($id) {
        return $this->invetsmentRepo->show($id);
    }

    public function update($id, $request) {
        return $this->invetsmentRepo->update($id, $request->all());
    }

    public function delete($id) {
        return $this->invetsmentRepo->delete($id);
    }
    
    public function invest($id) {
        return $this->invetsmentRepo->invest($id);
    }
}