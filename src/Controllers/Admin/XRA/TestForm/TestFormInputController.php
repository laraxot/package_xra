<?php
namespace XRA\XRA\Controllers\Admin\XRA\TestForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//--- services
use XRA\Extend\Services\ThemeService;


class TestFormInputController extends Controller{

	public function getFormComponents(){
		$view_path=dirname(\View::getFinder()->find('backend::includes.components.form.text')); //devo dargli una view esistente di partenza
		$files = \File::allFiles($view_path);
		$rows=[];
		foreach($files as $file){
			$filename=$file->getRelativePathname();
			$ext='.blade.php';
			if(ends_with($filename,$ext)){
				$base=substr(($filename),0,-strlen($ext));
				$name=str_replace(DIRECTORY_SEPARATOR,'_',$base);
				$name='bs'.studly_case($name);
				$comp_view=str_replace('/','.',$base);
				//echo '<br/>'.$base.'  --- '.$name.'  --  '.$comp_view;
				$tmp=new \stdClass();
				$tmp->base=$base;
				$tmp->name=$name;
				$tmp->comp_view=$comp_view;
				$rows[]=$tmp;
				
				//echo '<br/>'.$name;
				/*
				Form::component($name, 'backend::includes.components.form.'.$comp_view,
					['name', 'value' => null,'attributes' => [],'lang'=>$lang]);
				*/
			}
		}
		return $rows;
	}

	public function index(Request $request){
		$rows=$this->getFormComponents();
		return ThemeService::view()->with('rows',$rows);
	}
	//--------------------------------------------
	public function edit(Request $request,$comp){
		$rows=$this->getFormComponents();
		return ThemeService::view()->with('rows',$rows);
	}	

}