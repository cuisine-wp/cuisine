<?php
/**
 * Plugin Name: Cuisine
 * Plugin URI: http://chefduweb.nl/cuisine
 * Description: A framework for WordPress developers.
 * Version: 3.0.0
 * Author: Luc Princen
 * Author URI: http://www.chefduweb.nl/
 * License: GPLv3
 * Bitbucket Plugin URI: https://bitbucket.org/chefduweb/cuisine
 * Bitbucket Branch:     master
 *
 * @package Cuisine
 * @category Core
 * @author Chef du Web
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// The directory separator.
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);


/**
 * Main class that bootstraps the framework.
 */
if (!class_exists('Cuisine')) {

    class Cuisine {

        /**
         * Framework bootstrap instance.
         *
         * @var \Cuisine
         */
        private static $instance = null;


        /**
         * Plugin directory name.
         *
         * @var string
         */
        private static $dirName = '';


        /**
         * All registered routes
         *
         * @var array
         */
        public $routes = array();


        /**
         * All registered templates
         *
         * @var array
         */
        public $templates = array();


        /**
         * All registered scripts
         *
         * @var array
         */
        public $scripts = array();


        /**
         * All registered JS vars
         *
         * @var array
         */
        public $jsVars = array();


        /**
         * All registered JS Shims
         *
         * @var array
         */
        public $shims = array();


        /**
         * All registered Nav Items
         *
         * @var array
         */
        public $navItems = array();




        private function __construct(){

            static::$dirName = static::setDirName(__DIR__);

            // Load plugin.
            $this->load();
        }


        /**
         * Load the framework classes.
         *
         * @return void
         */
        private function load(){

            //auto-loads all .php files in these directories.
            $includes = array(
                'Classes/Wrappers',
                'Classes/Utilities',
                'Classes/Database/Contracts',
                'Classes/Database/Grammars',
                'Classes/Database',
                'Classes/Database/Migrations',
                'Classes/Models',
                'Classes/Cron',
                'Classes/Admin',
                'Classes/Front',
                'Classes/Builders',
                'Classes/Fields',
                'Classes/View'
            );

            if ( defined('WP_CLI') && WP_CLI )
                $includes[] = 'Classes/Cli';



            $includes = apply_filters( 'cuisine_autoload_dirs', $includes );

            foreach( $includes as $inc ){

                $root = static::getPluginPath();
                $files = glob( $root.$inc.'/*.php' );

                foreach ( $files as $file ){

                    require_once( $file );

                }
            }



            // Set the framework paths and starts the framework.
            add_action('after_setup_theme', array($this, 'bootstrap'));

            //cuisine is fully loaded
            do_action( 'cuisine_loaded' );

            //include the css
            add_action( 'admin_init', function(){
                wp_enqueue_style( 'cuisine', plugins_url( 'Assets/css/admin.css', __FILE__ ) );
            });
        }



        /**
         * Define paths and bootstrap the framework.
         *
         * @return void
         */
        public function bootstrap(){
            /**
             * Define all framework paths
             * These are real paths, not URLs to the framework files.
             * These paths are extensible with the help of WordPress
             * filters.
             */
            // Framework paths.
            $paths = apply_filters('cuisine_framework_paths', array());

            // Plugin base path.
            $paths['plugin'] = static::getPluginPath();

            // Register globally the paths
            foreach ($paths as $name => $path){

               if ( !isset( $GLOBALS['cuisine_paths'][$name] ) ){

                   $GLOBALS['cuisine_paths'][$name] = realpath($path).DS;

               }
            }

            do_action( 'cuisine_bootstrapped' );
        }




        /*=============================================================*/
        /**             Getters & Setters                              */
        /*=============================================================*/


        /**
         * Init the framework classes
         *
         * @return \Cuisine
         */
        public static function getInstance(){

            if ( is_null( static::$instance ) ){
                static::$instance = new static();
            }
            return static::$instance;
        }

        /**
         * Set the plugin directory property. This property
         * is used as 'key' in order to retrieve the plugins
         * informations.
         *
         * @param string
         * @return string
         */
        private static function setDirName($path) {

            $parent = static::getParentDirectoryName(dirname($path));

            $dirName = explode($parent, $path);
            $dirName = substr($dirName[1], 1);

            return $dirName;
        }

        /**
         * Check if the plugin is inside the 'mu-plugins'
         * or 'plugin' directory.
         *
         * @param string $path
         * @return string
         */
        private static function getParentDirectoryName($path) {

            // Check if in the 'mu-plugins' directory.
            if (WPMU_PLUGIN_DIR === $path) {
                return 'mu-plugins';

            }

            // Install as a classic plugin.
            return 'plugins';
        }


        public static function getPluginPath(){
            return __DIR__.DS;
        }

        /**
         * Returns the directory name.
         *
         * @return string
         */
        public static function getDirName(){
            return static::$dirName;
        }
    }
}


/**
 * Load the main class.
 *
 */
add_action('plugins_loaded', function(){

    $GLOBALS['Cuisine'] = Cuisine::getInstance();

});


/**
 * Print_R in a <pre> tag
 */
if( !function_exists( 'cuisine_dump' ) ){
    function cuisine_dump( $arr ){
        echo '<pre>';
            print_r( $arr );
        echo '</pre>';
    }
}

/**
 * Print_R in a <pre> tag and die
 */
if( !function_exists( 'dd' ) ){
    function dd( $arr ){
        cuisine_dump( $arr );
        die();
    }
}