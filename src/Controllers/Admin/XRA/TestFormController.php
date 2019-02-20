<?php
namespace XRA\XRA\Controllers\Admin\XRA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//--- services
use XRA\Extend\Services\ThemeService;


class TestFormController extends Controller{
	public function index(Request $request){

		return ThemeService::view();
	}
}