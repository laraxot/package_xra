<?php

namespace XRA\XRA\Controllers\Admin\XRA;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\Controller;

use XRA\Extend\Traits\CrudSimpleTrait as CrudTrait;
use XRA\Extend\Traits\ArtisanTrait;
use XRA\Extend\Services\ThemeService;

use File;
use Storage;

class TestImgController extends Controller{
	
	public function Croppic(Request $request){
		$row=$request;
		$view=CrudTrait::getView();
 		$routename = \Route::current()->getName();
 		return view($view)
 				->with('routename',$routename)
 				->with('view',$view)
 				->with('row',$row);
	}
}