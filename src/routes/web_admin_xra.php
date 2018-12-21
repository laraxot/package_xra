<?php
use XRA\Extend\Traits\RouteTrait;

//die('['.__LINE__.']['.__FILE__.']');

$namespace=$this->getNamespace();
$area=class_basename($namespace);




$item0=[
    'name'=>$area,
    'only'=>['index'],
    'subs'=>[
        [
            'name'=>'Test',
            'only'=>['index'],
            'acts'=>[
                ['name'=>'inputForm',],//end act_n
                ['name'=>'dateInputForm',],//end act_n
                ['name'=>'dateRangeInputForm',],//end act_n
                ['name'=>'wysiwygInputForm',],//end act_n
                ['name'=>'googleInputForm',],//end act_n
                ['name'=>'uploadInputForm',],//end act_n
                ['name'=>'chunkUploadInputForm',],//end act_n
                ['name'=>'btnForm',],//end act_n
                ['name'=>'navForm',],//end act_n
            ],//end acts
        ],//end sub_n
        [
            'name'=>'TestImg',
            'only'=>['index'],
            'acts'=>[
                ['name'=>'Croppic',],//end act_n
            ],//end acts
        ],//end sub_n
        [
            'name'=>'TestTnt',
            'only'=>['index'],
            'acts'=>[
                ['name'=>'BooleanSearch',],//end act_n
                ['name'=>'FuzzySearch',],//end act_n
                ['name'=>'GeoSearch',],//end act_n
            ],//end acts
        ],//end sub_n
        [
            'name'=>'FB',
            'only'=>['index'],
            'acts'=>[
                ['name'=>'Token',],//end act_n
                ['name'=>'Messenger',],//end act_n
                //['name'=>'GeoSearch',],//end act_n
            ],//end acts
        ],//end sub_n
    ],//end subs
];

$areas_prgs=[
    $item0
];

Route::group(
    ['prefix' => 'admin','middleware' => ['web','auth'],'namespace'=>$namespace.'\Controllers\Admin'],
    function () use ($areas_prgs) {
        RouteTrait::dynamic_route($areas_prgs);
    }
);
