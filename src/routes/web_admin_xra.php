<?php
use XRA\Extend\Traits\RouteTrait;
use XRA\Extend\Services\RouteService;

//die('['.__LINE__.']['.__FILE__.']');

$namespace = $this->getNamespace();
$area = class_basename($namespace);

$item0 = [
    'name' => $area,
    'param_name'=>'',
    'only' => ['index'],
    'subs' => [
        [
            'name' => 'Test',
            'only' => ['index'],
            'acts' => [
                ['name' => 'inputForm'], //end act_n
                ['name' => 'dateInputForm'], //end act_n
                ['name' => 'dateRangeInputForm'], //end act_n
                ['name' => 'wysiwygInputForm'], //end act_n
                ['name' => 'googleInputForm'], //end act_n
                ['name' => 'uploadInputForm'], //end act_n
                ['name' => 'chunkUploadInputForm'], //end act_n
                ['name' => 'btnForm'], //end act_n
                ['name' => 'navForm'], //end act_n
            ], //end acts
        ], //end sub_n
        [
            'name'=>'TestForm',
            'param_name'=>'',
            'subs'=>[
                ['name'=>'TestFormInput'],
                ['name'=>'TestFormBtn'],
            ],//end subs
        ],
        [
            'name' => 'TestImg',
            'only' => ['index'],
            'acts' => [
                ['name' => 'Croppic'], //end act_n
            ], //end acts
        ], //end sub_n
        [
            'name' => 'TestTnt',
            'only' => ['index'],
            'acts' => [
                ['name' => 'BooleanSearch'], //end act_n
                ['name' => 'FuzzySearch'], //end act_n
                ['name' => 'GeoSearch'], //end act_n
            ], //end acts
        ], //end sub_n
        [
            'name' => 'FB',
            'only' => ['index'],
            'acts' => [
                ['name' => 'Token'], //end act_n
                ['name' => 'Messenger'], //end act_n
                //['name'=>'GeoSearch',],//end act_n
            ], //end acts
        ], //end sub_n
    ], //end subs
];

$areas_prgs = [
    $item0,
];

$namespace = $this->getNamespace().'\Controllers\Admin';

Route::group(
    [
    'prefix' => 'admin',
    'middleware' => ['web', 'auth'],
    'namespace' => $namespace,
    ],
    function () use ($areas_prgs,$namespace) {
        //\XRA\Extend\Library\RouteTrait::dynamic_route($areas_prgs);
        RouteService::dynamic_route($areas_prgs, null, $namespace);
    }
);
