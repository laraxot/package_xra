<?php
namespace XRA\XRA\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use XRA\Extend\Traits\Updater;


class XotModel extends Model{
    use Searchable;
    use Updater;
}