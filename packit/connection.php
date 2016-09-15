<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Alpackits update-checker for packit ab2a538e2cad450facae28bc04eec6e3
class Packit_ab2a538e2cad450facae28bc04eec6e3_UpdateController{

    /**
     * Base url
     *
     * @var string
     */
    const BASE_URL = 'http://alpackit.dev';

    /**
     * API Version
     *
     * @var string
     */
    const API_VERSION = 'v1.0';


    /**
     * Method
     *
     * @var string
     */
    const METHOD = 'dev';


    /**
     * The packits UUID
     *
     * @var string
     */
    const UUID = 'ab2a538e-2cad-450f-acae-28bc04eec6e3';

    /**
     * Keeps the connection location
     *
     * @var string
     */
    const LOCATION = '/packit/connection.php';


    /**
     * The current plugin slug
     *
     * @var string
     */
    protected $slug;

    /**
     * Plugin slug
     *
     * @var string
     */
    protected $pluginSlug;


    /**
     * Alpackit license
     *
     * @var array
     */
    protected $license;


    /**
     * Constructor
     */
    public function __construct()
    {
        //get the plugin slug:
        $this->slug = str_replace( self::LOCATION, '', plugin_basename( __FILE__ ) );
        $this->pluginSlug = $this->makePluginSlug();

        //check if a license is send back:
        if( isset( $_GET['license'] ) ){
            $license = array( 'key' => $_GET['license'], 'expires' => strtotime( '+1 year' ) );
            update_option( static::UUID.'.license', $license );
            Header('Location: '. remove_query_arg( 'license' ) );
        }

        $this->license = static::getLicense();

        if( self::METHOD == 'dev' ){

            //no plugin transients:
            set_site_transient( 'update_plugins', null );

            //output all result information:
            add_filter( 'plugins_api_result', array( &$this, 'resultInfo' ), 100, 3 );

        }



        if( $this->pluginSlug !== null )
            $this->setEvents();



    }


    /**
     * Set events for updates and license checks
     *
     */
    public function setEvents()
    {
        //only add these events when a valid license is present:
        if( static::hasValidLicense() ){

            //Add the filter for plugin-update checks
            add_filter( 'site_transient_update_plugins', array( &$this, 'checkForUpdate' ), 100, 1 );
            add_filter( 'transient_update_plugins', array( &$this, 'checkForUpdate' ), 100, 1 );

            // Take over the Plugin info screen
            add_filter('plugins_api', array( &$this, 'updateInfo' ), 10, 3);

            // Add custom buttons to the plugin overview-screen

        }else{

            // Create the 'Add license' notifcation
            add_action( 'admin_notices', array( &$this, 'licenseNag' ) );

            // Create the 'Add license' button
            add_action( 'plugin_action_links', array( &$this, 'licenseButton' ), 100, 2 );
            add_action( 'network_admin_plugin_action_links', array( &$this, 'licenseButton' ), 100, 2 );

        }
    }



    /**********************************************/
    /***        Updates:
    /**********************************************/

    /**
     * Run plugin checks, on the update_plugin filter
     *
     * @return array
     */
    public function checkForUpdate( $data )
    {
        global $wp_version;

        if ( !isset( $data ) || empty( $data->checked ) )
            return $data;

        try{

            //make a remote call:
            $response = wp_remote_get( $this->getUrl() );

             //check if the response was valid:
            if( is_wp_error( $response ) )
                throw new Exception( $response->get_error_message() );

            //check if Alpackit returned a 200 response:
            if( $response['response']['code'] != 200 )
                throw new Exception( $response['response']['message'] );


            //body is a json:
            $response = json_decode( $response['body'] );

            //check if json wasn't empty:
            if( !is_object( $response ) || empty( $response ) )
                throw new Exception( 'Response couldn\'t be parsed' );

            // Feed the update data into WP updater
            //build the update object
            $update = new stdClass();
            $update->slug = $this->slug;
            $update->plugin = $this->pluginSlug;
            $update->new_version = $response->version;
            $update->url = self::BASE_URL.self::UUID;
            $update->package = $response->download;

            //pass the update object
            $data->response[  $this->pluginSlug ] = $update;


        } catch( Exception $e ) {

            echo $e->getMessage();

        }

        return $data;
    }




    /**
     * Get version info
     *
     * @param  array $data
     * @return array $data ( altered )
     */
    public function info( $data )
    {
        return $data;
    }


    /**
     * Output results of a plugin api request
     *
     * @return void
     */
    public function resultInfo( $result )
    {
        echo '<pre>';
            print_r( $result );
        echo '</pre>';

        return $result;
    }

    /**********************************************/
    /***        License logic:
    /**********************************************/

    /**
     * Shows the license-button:
     *
     * @return string
     */
    public function licenseButton( $actions, $pluginFile )
    {
        if( $pluginFile == $this->pluginSlug ){

            $html = '<a href="'.$this->getLicenseUrl().'" target="_blank">';
                $html .= apply_filters( 'alpackit_button_text', 'Add license' );
            $html .= '</a>';

            $actions[ 'license' ] = $html;
        }
    }

    /**
     * Add an admin license nag
     *
     * @return string
     */
    public function licenseNag()
    {

        $msg = apply_filters( 'alpackit_license_nag_message', 'It seems you do not have a license for '.$this->slug.' yet...' );
        echo '<div class="notice notice-error">';
            echo '<p>'.$msg.'</p>';
            echo '<a href="'.$this->getLicenseUrl().'" target="_blank" style="float:right;margin-top:-30px;">';
                echo apply_filters( 'alpackit_license_nag', 'Why not add one?' );
            echo '</a>';
        echo '</div>';

    }


    /**********************************************/
    /***        License checks:
    /**********************************************/

    /**
     * Checks if this domain is licensed
     *
     * @param  bool $checkRemote - force a new http request to check
     */
    public static function hasValidLicense( $checkRemote = false ){

        //get the local license:
        $_license = static::getLicense();

        //check if it's available:
        if( !static::licenseSet( $_license ) )
            return false;

        //check if it's expired:
        if(
            !isset( $_license[ 'expires' ] ) ||
            $_license[ 'expires' ] < time()
        )
            return false;


        return true;
    }

    /**
     * Checks if the license isn't an empty array and the key is set
     *
     * @return bool
     */
    public static function licenseSet( $_license = array() )
    {
        if( empty( $_license ) || !isset( $_license['key'] ) )
            return false;

        return true;
    }


    /**********************************************/
    /***        Helpers:
    /**********************************************/



    /**
     * Get the alpackit url where we can check the license
     *
     * @return string
     */
    public function getUrl()
    {
        $url = trailingslashit( self::BASE_URL );
        $url .= 'remote/'.trailingslashit( self::API_VERSION );

        $url .= 'wordpress/license/';
        $url .= $this->license['key'];
        $url .= '/packit/info';

        return $url;

    }



    /**
     * Get the alpackit url where we can check the license
     *
     * @return string
     */
    public function getLicenseUrl()
    {
        $url = trailingslashit( self::BASE_URL );
        $url .= 'clientconnect/';
        $url .= trailingslashit( self::UUID );
        $url .= trailingslashit( $this->getDomain() );
        $url .= '?callback_url='.$this->getCurrentUrl();

        return $url;

    }

    /**
     * Returns the domain of this website
     *
     * @return string
     */
    public function getDomain()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Get the current url
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        $url = '';

        // Check to see if it's over https
        if ( isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ) {
            $url .= 'https://';
        } else {
            $url .= 'http://';
        }

        // Was a username or password passed?
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $url .= $_SERVER['PHP_AUTH_USER'];

            if (isset($_SERVER['PHP_AUTH_PW'])) {
                $url .= ':' . $_SERVER['PHP_AUTH_PW'];
            }

            $url .= '@';
        }


        // We want the user to stay on the same host they are currently on,
        // but beware of security issues
        // see http://shiflett.org/blog/2006/mar/server-name-versus-http-host
        $url .= $_SERVER['HTTP_HOST'];

        // Get the rest of the URL
        if (!isset($_SERVER['REQUEST_URI'])) {
            // Microsoft IIS doesn't set REQUEST_URI by default
            $url .= $_SERVER['PHP_SELF'];

            if (isset($_SERVER['QUERY_STRING'])) {
                $url .= '?' . $_SERVER['QUERY_STRING'];
            }
        } else {
            $url .= $_SERVER['REQUEST_URI'];
        }

        return $url;
    }


    /**
     * Get the license object, if it's saved
     * Defaults to an empty array
     *
     * @return array
     */
    public static function getLicense()
    {
        return get_option( static::UUID.'.license', array() );
    }


    /**
     * Generate the plugin slug
     *
     * @return string
     */
    private function makePluginSlug(){

        $active = get_option( 'active_plugins' );
        foreach( $active as $plugin ){

            if( strpos( $plugin, $this->slug ) !== false )
                return $plugin;

        }

        return null;
    }

}

/**
 * Helper functions:
 */


/**
 * Soft-checks a license
 *
 * @param  string $uuid  - Packit uuid
 * @return bool
 */
if( !function_exists( 'packit_has_license' ) ){

    function packit_has_license( $uuid = null ){

        $class = packit_get_class_name( $uuid );
        return $class::hasValidLicense();
    }
}


/**
 * Does a remote-check to see if a packit has a valid license
 * @param  string $uuid - Packit uuid
 * @return bool
 */
if( !function_exists( 'packit_check_license' ) ){

    function packit_check_license( $uuid = null ){

        $class = packit_get_class_name( $uuid );
        return $class::hasValidLicense( true ); //hard-check
    }
}


/**
 * Return the generated class name
 * @param  string $uuid
 * @return string
 */
if( !function_exists( 'packit_get_class_name' ) ){

    function packit_get_class_name( $uuid = null ){

        if( $uuid == null )
            throw new Exception( 'No uuid given' );

        $prefix = strtolower( str_replace( array( ' ', '-', '_' ), '', $uuid ) );
        return 'Packit_{$prefix}_UpdateController';
    }
}


//fire the class once:
new Packit_ab2a538e2cad450facae28bc04eec6e3_UpdateController();



