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
use TeamTNT\TNTSearch\TNTSearch;
use TeamTNT\TNTSearch\TNTGeoSearch;
use TeamTNT\TNTSearch\Indexer\TNTGeoIndexer;

// esempi presi da https://github.com/teamtnt/tntsearch

class TestTntController extends Controller
{
    private $tnt=null;

    public function initTnt()
    {
        $tnt = new TNTSearch();
        $driver = config('database.default');
        $config = config('scout.tntsearch') + config("database.connections.$driver");
        $tnt->loadConfig($config);
        $this->tnt=$tnt;
    }

    public function BooleanSearch(Request $request)
    {
        if ($this->tnt==null) {
            $this->initTnt();
        }
        $this->tnt->selectIndex("name.index");
        //this will return all documents that have romeo in it but not juliet
        $res = $this->tnt->searchBoolean("romeo -juliet");
        //returns all documents that have romeo or hamlet in it
        $res = $this->tnt->searchBoolean("romeo or hamlet");
        //returns all documents that have either romeo AND juliet or prince AND hamlet
        $res = $this->tnt->searchBoolean("(romeo juliet) or (prince hamlet)");
    }

    public function FuzzySearch(Request $request)
    {
        if ($this->tnt==null) {
            $this->initTnt();
        }
        /*
        public $fuzzy_prefix_length  = 2;
        public $fuzzy_max_expansions = 50;
        public $fuzzy_distance       = 2 //represents the levenshtein distance;
        */
        $this->tnt->selectIndex("name.index");
        $this->tnt->fuzziness = true;

        //when the fuzziness flag is set to true the keyword juleit will return
        //documents that match the word juliet, the default levenshtein distance is 2
        $res = $this->tnt->search("juleit");
    }

    public function GeoSearch(Request $request)
    {
        $currentLocation = [
            'latitude'  => 44.4933457,
            'longitude' => 11.3449207,
        ];

        $distance = 2000; //km
        $model=new \XRA\Food\Models\Restaurant;
        $this->scoutGeoImport(['model'=>$model]);

        $index=$model->searchableAs().'.geo.index';
        $driver = config('database.default');
        $config = config('scout.tntsearch') + config("database.connections.$driver");
        $candyShopIndex = new TNTGeoSearch();
        $candyShopIndex->loadConfig($config);
        $candyShopIndex->selectIndex($index);

        $candyShops = $candyShopIndex->findNearest($currentLocation, $distance, 1000);

        /*
        dd($candyShops);
         "ids" => array:259 [▶]
        "distances" => array:259 [▶]
        "hits" => 259
        "execution_time" => "3.13 ms"
        where in non restituisce nell'ordine di ids, percio' mi creo un array di supporto
        */
        $distances=array_combine($candyShops['ids'], $candyShops['distances']);
        $rows= \XRA\Blog\Models\Post::whereIn('post_id', $candyShops['ids'])
                ->where('lang', 'it')
                ->where('type', 'restaurant')
                ->where('guid', '!=', 'restaurant')
                ->get();

        $rows=$rows->map(function ($row, $k) use ($distances) {
            $row->distance = $distances[$row->post_id];
            return $row;
        })->sortBy('distance');

        echo '<ol>';
        foreach ($rows as $row) {
            echo '<li>'.$row->title.' ['.$row->distance.'] </li>';
        }
        echo '</ol>';
        dd($rows[0]);
    }


    public function scoutGeoImport($params)
    {
        extract($params);
        $index=$model->searchableAs().'.geo.index';
        $driver = config('database.default');
        $config = config('scout.tntsearch') + config("database.connections.$driver");
        $indexer = new TNTGeoIndexer;
        $indexer->loadConfig($config);
        
        $indexer->createIndex($index);
        $indexer->setPrimaryKey($model->getKeyName());
        $fields = implode(', ', array_keys($model->toSearchableArray()));
        $query = "{$model->getKeyName()}, $fields";
        if ($fields == '') {
            $query = '*';
        }
        $indexer->query("SELECT $query FROM {$model->getTable()};");
        $indexer->run();
    }


    public function scoutImport($params)
    {
        extract($params);
        $tnt = new TNTSearch();
        $driver = config('database.default');
        $config = config('scout.tntsearch') + config("database.connections.$driver");
        $tnt->loadConfig($config);
        //app('db')->connection()->getPdo()
        $pdo=$model->getConnection()->getPdo();
        $tnt->setDatabaseHandle($pdo);
        //$tnt->setDatabaseHandle(app('db')->connection()->getPdo());
        $indexer = $tnt->createIndex($model->searchableAs().'.index');
        $indexer->setPrimaryKey($model->getKeyName());
        $fields = implode(', ', array_keys($model->toSearchableArray()));
        $query = "{$model->getKeyName()}, $fields";
        if ($fields == '') {
            $query = '*';
        }
        $indexer->query("SELECT $query FROM {$model->getTable()};");
        $indexer->run();
        //return ArtisanTrait::exe('scout:import '.get_class($model));
           // $comando='scout:import '.get_class($model);
           // Artisan::call($comando);
           // return '[<pre>' . Artisan::output() . '</pre>]';
    }
}
