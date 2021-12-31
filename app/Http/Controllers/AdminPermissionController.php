<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AdminPermissionController extends Controller
{
    //
    public function make_perm()
    {
        // $permission = Permission::create(['guard_name' => 'admin', 'name' => 'publish articles']);
        $permission= Permission::find(1);
        // dd($permission);

        $admin=Admin::find(9);
        // dd($admin);
        // $admin->givePermissionTo($permission);
        dd($admin->hasPermissionTo('publish articles', 'admin'));

    }
}
