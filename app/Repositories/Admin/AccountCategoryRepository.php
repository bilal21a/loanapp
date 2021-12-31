<?php 
namespace App\Repositories\Admin;

use App\AccountCategory;
use App\Http\Resources\AccountCategoryResource;

class AccountCategoryRepository {
    
    protected $accountCategory; 

    public function __construct(AccountCategory $accountCategory) {
        $this->accountCategory = $accountCategory;
    }

    public function index() {
        $data = $this->accountCategory->where("parent", null)->get();//->take(100)->paginate(50);
        return AccountCategoryResource::collection($data);
    }

    public function create() {
        $data = AccountCategory::orderBy("type", "asc")->get();
        return view("website.admin.account.new-category")->withCategories($data);
    }

    public function store($request) {
        $newCategory = $this->accountCategory->create([
            "name" => $request->name,
            "type" => $request->type,
            "parent" => $request->parentcat,
            "interest_rate" => $request->interest,
            "interest_interval" => $request->interest_interval,
            "status" => 1, 
        ]);

        if($newCategory) {
            return back()->withMessage("Account category created");
        }

        return back()->withErrors("An error creating your category. Try again");
    }

    public function getCategory($id) {
         $data = $this->accountCategory->find($id);

         return view("website.admin.account.edit")->withAccount(new AccountCategoryResource($data))
                        ->withCategories($this->index());
    }

    public function update($id, array $request) {
        $found = $this->accountCategory->find($id);

        if($found) {
            $found->update($request);
            return back()->withMessage("Category updated");
        }

        return back()->withErrors("No matching category found.");
    }

    public function delete($id) {
        $found = $this->accountCategory->find($id);
        if($found) {
            $found->delete();
            return back()->withMessage("Category deleted");
        }
        return back()->withErrors("Error deleting category.");
    }
}