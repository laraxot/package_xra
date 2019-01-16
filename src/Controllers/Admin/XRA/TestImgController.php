<?php



namespace XRA\XRA\Controllers\Admin\XRA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//--- services
use XRA\Extend\Services\ThemeService;

class TestImgController extends Controller
{
    public function Croppic(Request $request)
    {
        $row = $request;
        $view = ThemeService::getView();
        $routename = \Route::current()->getName();

        return view($view)
                ->with('routename', $routename)
                ->with('view', $view)
                ->with('row', $row);
    }
}
