<?php

namespace Cuisine\Wrappers;

class Sass extends Wrapper {

    /**
     * Return the igniter service key responsible for the Sass class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'sass';
    }

}
