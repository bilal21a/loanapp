<?php 
namespace App\Services\Admin;

use App\Repositories\Admin\InvestmentRepository;

class InvestmentService {
    
    protected $invetsmentRepo;

    public function __construct(InvestmentRepository $invetsmentRepo) {
        $this->invetsmentRepo = $invetsmentRepo;
    }

    public function index() {
        return $this->invetsmentRepo->index();
    }

    public function create() {
        return $this->invetsmentRepo->create();
    }

    public function store($request) {
        return $this->invetsmentRepo->store($request);
    }

    public function edit($id) {
        return $this->invetsmentRepo->edit($id);
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

    public function investors($id) {
        return $this->invetsmentRepo->investors($id);
    }
    public function invest($id) {
        return $this->invetsmentRepo->invest($id);
    }
}