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

class TestController extends Controller{
    public function index(Request $request){
        if ($request->routelist==1) {
            return ArtisanTrait::exe('route:list');
        }
        $view=CrudTrait::getView();
        return view($view);
    }//end function
 	public function inputForm(Request $request){
 		$view=CrudTrait::getView();
 		$routename = \Route::current()->getName();
 		
 		$components=['bsText','bsTextarea','bsSelect',
 				'bsCheckbox','bsYesNo',
 				'bsNumber','bsInteger','bsDecimal','bsEuro',
 				'bsMultiCheck',
 			];
 		//'bsMultiSelect','bsMultiSelectCollection','bsGridStack','bsTypeahead','bsChips'
 		$uploadComponents=['bsUpload','bsUploadBlueImp','bsUnisharpFileMan',];
 		$wysiwygComponents=['bsTinymce',];
 		$googleComponents=['bsGeoComplete','bsGeo',];
 		$dateComponents=['bsSelectMonth','bsSelectDay','bsDate','bsTime','bsDateTime',
 						'bsDateTimeLocal','bsDateRange','bsDateTimeRange','bsDateTimeRangeX','bsDateTimeRangePicker',];
 		$macros=['bsYearNav','bsMonthYearNav','bsFormSearch','bsDateTimeRangePicker_c'];

 		$btnMacros=['bsBtnRoute','bsBtnCrud','bsBtnEdit','bsBtnClone','bsBtnDelete','bsBtnCreate','bsBtnBack'];
 		/*
 		echo '<pre>';print_r($blades);echo '</pre>';
 		$blades1=scandir($dir);
 		echo '<pre>';print_r($blades1);echo '</pre>';
 		die();
 		*/
 		$row=$request;
 		$row->bsText=3;
 		$row->bsYesNo=1;
 		$row->bsEuro=5.33;
 		return view($view)
 			->with('view',$view)
 			->with('routename',$routename)
 			->with('components',$components)
 			->with('row',$row);
 	}
 	public function dateInputForm(Request $request){
 		$view=CrudTrait::getView();
 		$routename = \Route::current()->getName();
 		$components=['bsUxDate','bsVaadinDate','bsSelectMonth','bsSelectDay','bsDate','bsTime','bsDateTime',
 						'bsDateTimeLocal',];
 		$row=$request;

 		return view($view)
 			->with('view',$view)
 			->with('routename',$routename)
 			->with('components',$components)
 			->with('row',$row);					
 	}
 	public function dateRangeInputForm(Request $request){
 		$view=CrudTrait::getView();
 		$routename = \Route::current()->getName();
 		$components=[
 				//'bsFlatDateRange',
				'bsFlatDateTimeRange',
				'bsUxDateRange','bsDateRange',
				'bsDateTimeRange','bsDateTimeRangeX','bsDateTimeRangePicker',
 					];
 		$row=$request;
 		$row->bsDateRange_start='2017-01-01';
 		$row->bsDateRange_end='2017-01-15';
 		
 		$row->bsDateTimeRange_start='2017-01-01 12:01:00';
 		$row->bsDateTimeRange_end='2017-01-15 18:00:00';
 		
 		return view($view)
 			->with('view',$view)
 			->with('routename',$routename)
 			->with('components',$components)
 			->with('row',$row);					
 	}
 	public function wysiwygInputForm(Request $request){
 		$view=CrudTrait::getView();
 		$routename = \Route::current()->getName();
 		$components=['bsTinymce',];
 		$row=$request;

 		return view($view)
 			->with('view',$view)
 			->with('routename',$routename)
 			->with('components',$components)
 			->with('row',$row);					
 	}
 	public function googleInputForm(Request $request){
 		$view=CrudTrait::getView();
 		$routename = \Route::current()->getName();
 		$components=['bsGeoComplete','bsGeo',];
 		$row=$request;

 		return view($view)
 			->with('view',$view)
 			->with('routename',$routename)
 			->with('components',$components)
 			->with('row',$row);					
 	}

 	public function uploadInputForm(Request $request){
 		$view=CrudTrait::getView();
 		$routename = \Route::current()->getName();
 		$components=['bsUpload','bsUploadBlueImp','bsUnisharpFileMan',];
 		$row=$request;

 		return view($view)
 			->with('view',$view)
 			->with('routename',$routename)
 			->with('components',$components)
 			->with('row',$row);					
 	}

    public function chunkUploadInputForm(Request $request){
        $view=CrudTrait::getView();
        $routename = \Route::current()->getName();
        $components=['bsChunkUpload',];
        $row=$request;

        return view($view)
            ->with('view',$view)
            ->with('routename',$routename)
            ->with('components',$components)
            ->with('row',$row);                 
    }



 	public function btnForm(Request $request){
 		$view=CrudTrait::getView();
 		$routename = \Route::current()->getName();
 		$components=[/*'bsBtnRoute',*//*'bsBtnCrud',*/'bsBtnEdit','bsBtnClone',/*'bsBtnDelete',*/'bsBtnCreate',/*'bsBtnBack'*/];
 		$row=$request;

 		return view($view)
 			->with('view',$view)
 			->with('routename',$routename)
 			->with('components',$components)
 			->with('row',$row);					
 	}
 	public function navForm(Request $request){
 		$view=CrudTrait::getView();
 		$routename = \Route::current()->getName();
 		$components=['bsYearNav','bsMonthYearNav'];
 		$row=$request;

 		return view($view)
 			->with('view',$view)
 			->with('routename',$routename)
 			->with('components',$components)
 			->with('row',$row);					
 	}

}//end class
