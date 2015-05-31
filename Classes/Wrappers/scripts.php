<?php

namespace Cuisine\Wrappers;

class Scripts extends Wrapper {

    /**
     * Return the igniter service key responsible for the Scripts class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'scripts';
    }

}
