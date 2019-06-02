<?php

namespace XRA\XRA\Relations;

use XRA\XRA\Relations\Custom;
use Closure;

trait HasCustomRelations
{
    /**
     * Define a custom relationship.
     *
     * @param  string  $related
     * @param  string  $baseConstraints
     * @param  string  $eagerConstraints
     * @return \App\Services\Database\Relations\Custom
     */
    public function custom($related, Closure $baseConstraints, Closure $eagerConstraints)
    {
        $instance = new $related;
        $query = $instance->newQuery();

        return new Custom($query, $this, $baseConstraints, $eagerConstraints);
    }
}
