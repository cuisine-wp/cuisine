<?php

namespace Cuisine\Wrappers;

class Script extends Wrapper {

    /**
     * Return the igniter service key responsible for the Script class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'script';
    }

}
