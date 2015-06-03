<?php

    namespace Cuisine\Wrappers;
    
    class StaticInstance {
    
        /**
         * Static bootstrapped instance.
         *
         * @var \Cuisine\Wrappers\StaticInstance
         */
        public static $instance = null;
    
    
    
        /**
         * Init the Assets Class
         *
         * @return \Cuisine\Admin\Assets
         */
        public static function getInstance(){
    
            return static::$instance = new static();

        }
    
    
    } 