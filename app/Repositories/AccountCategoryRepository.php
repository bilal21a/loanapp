<?php 
namespace App\Repositories;

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

    public function create($request) {
        $newCategory = $this->accountCategory->create([
            "name" => $request->name,
            "type" => $request->type,
            "parent" => $request->categoryId,
            "status" => 1,
        ]);

        if($newCategory) {
            return back()->withMessage("Category created");
        }

        return back()->withMessage("Error creating category");
    }

    public function getCategory($id) {
        return $this->accountCategory->findOrFail($id);
    }

    public function update($id, $request) {
        $found = $this->accountCategory->find($id);

        if($found) {
            $this->found->update([
                "name" => $request->name,
                "type" => $request->type,
                "parent" => $request->categoryId,
                "status" => $request->status,
            ]);
            return back()->withMessage("Category updated")->withCategory($found);
        }

        return back()->withErrors(["Category not found"]);
    }

    public function delete($id) {
        $found = $this->accountCategory->find($id);
        if($found) {
            $found->delete();
            return back()->withMessage("Category deleted");
        }
        return back()->withErrors(["Error deleting category."]);
    }
}