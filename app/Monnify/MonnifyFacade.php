<?php 
namespace App\Monnify;

use Illuminate\Support\Facades\Facade;

class MonnifyFacade extends Facade {

    protected static function getFacadeAccessor() {
        return "monnify";
    }
    
}