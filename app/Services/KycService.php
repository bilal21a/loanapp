<?php
namespace App\Services;

use App\Repositories\KycRepository;

class KycService {
  protected $kycRepository;

  public function __construct(kycRepository $kycRepository) {
    $this->kycRepository = $kycRepository;
  }

  public function store($request) {
    return $this->kycRepository->store($request);
  }

  public function show($id) {
    return $this->kycRepository->show($id);
  }

  public function update($request) {
    return $this->kycRepository->update($request);
  }

  public function delete($id) {
    return $this->kycRepository->delete($id);
  }

  public function socialHandle($request) {
      return $this->kycRepository->socialHandle($request);
  }

  public function employmentHistory($request) {
     return $this->kycRepository->employmentHistory($request);
  }

}
