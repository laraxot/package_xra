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
        $pieces=explode('\\', get_class());
        $pack=strtolower($pieces[1]);
        //ddd($pieces);
        $pieces=array_slice($pieces, 3);
        $pieces=collect($pieces)->map(function ($item) {
            return snake_case($item);
        })->all();
        $trad_name=$pack.'::'.implode('.', $pieces);
        $trad=trans($trad_name);
        if (!is_array($trad)) {
            ddd($trad_name.' is not an array');
            $trad = [];
        }
        $tradGeneric = trans('extend::generic'); //deve funzionare anche senza il pacchetto "food", invece "extend" e' un pacchetto primario
        $trad = array_merge($tradGeneric, $trad);
        return $trad;
    }
}
