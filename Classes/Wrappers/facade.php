<?php
namespace Cuisine\Wrappers;

abstract class Wrapper {

    /**
     * The Application instance.
     *
     * @var \Cuisine\Core\Application
     */
    protected static $app;

    /**
     * The resolved object instances.
     *
     * @var array
     */
    protected static $resolvedInstances;

    /**
     * Each facade must define their igniter service
     * class key name.
     *
     * @throws \RuntimeException
     * @return string
     */
    protected static function getFacadeAccessor() {
        
        throw new \RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    /**
     * Retrieve the instance called by the igniter service.
     *
     * @return mixed
     */
    public static function getFacadeRoot() {
        /**
         * Grab the igniter service class and get the instance
         * called by the service.
         */
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * Return a facade instance if one already exists. If not, keep a copy
     * of all instances and return the current called one.
     *
     * @param string $name
     * @return mixed
     */
    private static function resolveFacadeInstance($name) {

        if (is_object($name)) return $name;

        return static::getFacadeName( $name );
    }


    private static function getFacadeName( $name ) {
        $aliases = array(
            'app'               => 'Cuisine\Core\Application',
            'asset'             => 'Cuisine\Asset\AssetFactory',
            'asset.finder'      => 'Cuisine\Asset\AssetFinder',
            'field'             => 'Cuisine\Fields\FieldBuilder',
            'loop'              => 'Cuisine\View\Loop',
            'metabox'           => 'Cuisine\Metabox\MetaboxBuilder',
            'page'              => 'Cuisine\Page\PageBuilder',
            'posttype'          => 'Cuisine\PostType\PostTypeBuilder',
            'router'            => 'Cuisine\Route\Router',
            'sections'          => 'Cuisine\Page\Sections\SectionBuilder',
            'taxonomy'          => 'Cuisine\Taxonomy\TaxonomyBuilder',
            'user'              => 'Cuisine\User\UserFactory',
            'validation'        => 'Cuisine\Validation\ValidationBuilder',
            'view'              => 'Cuisine\View\ViewFactory'
        );

        return $aliases[ $name ];
    }


    /**
     * Clear a resolved facade instance.
     *
     * @param string $name
     * @return void
     */
    public static function clearResolvedInstance($name) {

        unset(static::$resolvedInstances[$name]);
    }

    /**
     * Clear all of the resolved instances.
     *
     * @return void
     */
    public static function clearResolvedInstances() {

        static::$resolvedInstances = array();
    }

    /**
     * Store the application instance.
     *
     * @param \Cuisine\Core\Application $app
     * @return void
     */
    public static function setFacadeApplication($app) {

        static::$app = $app;
    }

    /**
     * Magic method. Use to dynamically call the registered
     * instance method.
     *
     * @param string $method The class method used.
     * @param array $args The method arguments.
     * @return mixed
     */
    public static function __callStatic($method, $args) {

        $instance = static::getFacadeRoot();
        $instance = new $instance();


        /**
         * Call the instance and its method.
         */
        return call_user_func_array(array($instance, $method), $args);
        //return $instance->$method( implode( ',', $args ) );
    }

} 