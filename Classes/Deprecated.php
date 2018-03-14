<?php

    namespace Cuisine;

    class Deprecated{

        /**
         * Array holding all deprecated filters
         *
         * @var Array
         */
        protected $filterMap;

        /**
         * Array holding all deprecated actions
         *
         * @var Array
         */
        protected $actionMap;


        public function __construct()
        {
            $this->setDeprecatedFilterMap();
            $this->setDeprecatedActionsMap();

		    foreach ( $this->filterMap as $new => $old ) {
			    add_filter( $new, [ $this, 'deprecatedFilterMapping' ], );
            }
            
            foreach( $this->actionMap as $new => $old ) {
                add_action( $new, [ $this, 'deprecatedActionMapping' ] );
            }
        }

        /**
         * The deprecated filter map
         *
         * @return void
         */
        public function setDeprecatedFilterMap()
        {
            $this->filterMap = [
                


            ];
        }
        
        /**
         * The deprecated action map
         *
         * @return void
         */
        public function setDeprecatedActionMap()
        {
            $this->actionMap = [

            ];
        }

        /**
         * Map a deprecated action
         *
         * @param Mixed $data
         * @param Mixed $arg_1
         * @param Mixed $arg_2
         * @param Mixed $arg_3
         * 
         * @return $data
         */
        public function deprecatedActionMapping( $data, $arg_1 = '', $arg_2 = '', $arg_3 = '' )
        {
            $actionMap = $this->actionMap;
        
            $filter = current_filter();
            if ( isset( $actionMap[ $filter ] ) ) {
                if ( has_action( $actionMap[ $filter ] ) ) {
                    $data = do_action( $actionMap[ $filter ], $data, $arg_1, $arg_2, $arg_3 );
                    if ( ! defined( 'DOING_AJAX' ) ) {
                        _deprecated_function( 'The ' . $actionMap[ $filter ] . ' action', '', $filter );
                    }
                }
            }
            return $data;
        }

         /**
         * Map a deprecated filter
         *
         * @param Mixed $data
         * @param Mixed $arg_1
         * @param Mixed $arg_2
         * @param Mixed $arg_3
         * 
         * @return $data
         */
        public function deprecatedFilterMapping( $data, $arg_1 = '', $arg_2 = '', $arg_3 = '' )
        {
            $filterMap = $this->filterMap;
        
            $filter = current_filter();
            if ( isset( $filterMap[ $filter ] ) ) {
                if ( has_filter( $filterMap[ $filter ] ) ) {
                    $data = apply_filters( $filterMap[ $filter ], $data, $arg_1, $arg_2, $arg_3 );
                    if ( ! defined( 'DOING_AJAX' ) ) {
                        _deprecated_function( 'The ' . $filterMap[ $filter ] . ' filter', '', $filter );
                    }
                }
            }
            return $data;
        }
    }