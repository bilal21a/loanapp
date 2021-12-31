<?php
namespace App\Repositories\Admin;

use App\Investment;
use App\Profile;
use App\UserInvestment;
use App\Traits\UploadAble;

class InvestmentRepository {

    protected $investment;
    protected $profile;
    protected $userinvestment;

    use UploadAble;

    public function __construct(Investment $investment, Profile $profile, UserInvestment $userinvestment) {
        $this->investment = $investment;
        $this->profile = $profile;
        $this->userinvestment = $userinvestment;
    }

    public function index() {
        $data = $this->investment->orderBy("created_at", "desc")->get();
        return view("website.admin.investment.index")->withInvestments($data);
    }

    public function create() {
        return view("website.admin.investment.new-investment");
    }

    public function store($request) {

        $photo = $request->has('file') ? $this->uploadOne($request->file, 'imgs') : "no-image.jpg";
        $referer_code = "MVF".time();
        $save = $this->investment->create([
            "name" => $request->name,
            "type" => $request->type,
            "description" => $request->description,
            "amount" => $request->amount,
            "duration" => $request->duration,
            "interest_rate" => $request->interest_rate,
            "cover_photo" => $photo,
            "amount_per_investor" => $request->amount_per_slot,
            //"referer_code" => $referer_code,
            "status" => 1,
        ]);

        if($save) {
            return back()->withMessage("Investment plan created");
        }

        return back()->withErrors("Could not create investment plan");
    }

    public function edit($id) {
        $data = $this->investment->findOrFail($id);
        return view("website.admin.investment.edit")->withInvestment($data);
    }

    public function show($id) {
        $investment = $this->investment->findOrFail($id);
        if($investment) {
            return back()->withInvestment($investment);
        }

        return back()->withErrors("Not found");
    }

    public function update($id, array $request) {
        $investment = $this->investment->findOrFail($id);
        if($investment->update($request)) {
            return back()->withMessage("Data updated");
        }

        return back()->withErrors("Could not update data");
    }

    public function delete($id) {
        $found = $this->investment->findOrFail($id);
        if($found) {
            $found->delete();
            return back()->withMessage("Record deleted");
        }

        return back()->withErrors("Could not delete record");
    }

    public function investors($id) {
        $investment = $this->investment->with("investors")->findOrFail($id);
        return view("website.admin.investment.investors")->withInvestment($investment);
    }

    public function imageUpload($request) {
        if($file = $request->file('file')) {
            $fileName = time().time().'.'.$request->file->getClientOriginalExtension();
            $target_dir = public_path('/imgs');

            if($file->move($target_dir, $fileName)) {
                $fileNameToStore = $fileName;
            } else {
                $fileNameToStore = "no-image.jpg";
            }
            return $fileNameToStore;
        }
    }
}
