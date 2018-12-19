<?php
namespace XRA\XRA\Traits;

trait FormRequestTrait{
	/**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages(){
		$class=str_replace('XRA\\Food\\Requests\\','',get_class());
		$class=str_replace('\\','.',$class);
		$class=snake_case($class);
		$trad=trans('food::'.$class);
		return $trad;
	}
}