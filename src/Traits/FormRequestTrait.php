<?php

namespace XRA\XRA\Traits;

trait FormRequestTrait
{
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $class = str_replace('XRA\\Food\\Requests\\', '', get_class());
        $class = str_replace('\\', '.', $class);
        $class = snake_case($class);
        $trad = trans('food::generic');
        $tradSpecific = trans('food::' . $class);
        if (is_array($tradSpecific)) {
            $trad = array_merge($trad, $tradSpecific);
        }

        return $trad;
    }
}
