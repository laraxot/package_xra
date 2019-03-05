<?php
namespace XRA\XRA\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//--- services
use XRA\Extend\Services\ThemeService;
//--- traits
use XRA\Extend\Traits\ArtisanTrait;

class XRAController extends Controller
{
    public function index(Request $request)
    {
        if ($request->act=='routelist') {
            return ArtisanTrait::exe('route:list');
        }
        return ThemeService::view();
    }

    //end function
 //
}//end class
