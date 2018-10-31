<?php

namespace XRA\XRA\Controllers\Admin;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\Controller;

use XRA\Extend\Traits\CrudSimpleTrait as CrudTrait;
use XRA\Extend\Traits\ArtisanTrait;


class XRAController extends Controller{
    public function index(Request $request){
        if ($request->routelist==1) {
            return ArtisanTrait::exe('route:list');
        }
        $view=CrudTrait::getView();
        return view($view);
    }//end function
 //
}//end class
