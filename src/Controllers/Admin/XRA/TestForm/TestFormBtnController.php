<?php
namespace XRA\XRA\Controllers\Admin\XRA\TestForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//--- services
use XRA\Extend\Services\ThemeService;


class TestFormBtnController extends Controller{
	public function index(Request $request){

		return ThemeService::view();
	}
}