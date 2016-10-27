<?php
namespace Cuisine\Builders;

use Cuisine\Utilities\Session;
use Cuisine\Utilities\User;

class SettingsTabBuilder {


    /**
     * SettingsTab instance data.
     *
     * @var Array
     */
    private $data;


    /**
     * The current user instance.
     *
     * @var \Cuisine\Utilities\User
     */
    private $user;


    /**
     * The settings page view, in raw html
     *
     * @var html
     */
    private $view;


    /**
     * Build a settings page instance.
     *
     * @param \Cuisine\Validation\Validation $validator
     * @param \Cuisine\User\User $user
     */
    function __construct(){

        $this->data = array();

    }


    /**
     * Set a new settings page.
     *
     * @param string $title The settings page title.
     * @param string $slug The settings page slug name.
     * @param array $options SettingsTab extra options.
     * @param \Cuisine\View\SettingsTabView
     * @return object
     */
    public function make( $title, array $contents = array() ){

        $this->data['title'] = $title;
        $this->data['slug'] = sanitize_title( $title );
        $this->data['fields'] = $contents;

        return $this;
    }

    /**
     * Render this settings tab:
     *
     * @param \WP_Post $post The WP_Post object.
     * @param array $datas The settings page $args and associated fields.
     * @throws SettingsTabException
     * @return void
     */
    public function render() {

        $this->setDefaultValue();

        echo '<div class="tab-content '.$this->data['slug'].'">';

            echo '<h2>'.$this->data['title'].'</h2>';

            foreach( $this->data['fields'] as $field ){

                $field->render();

            }

            //render the javascript-templates seperate, to prevent doubles
            $rendered = array();

            foreach( $this->data['fields'] as $field ){

                if( method_exists( $field, 'renderTemplate' ) && !in_array( $field->name, $rendered ) ){

                    echo $field->renderTemplate();
                    $rendered[] = $field->name;

                }
            }

        echo '</div>';
    }

    /**
     * Returns an array of fields for this tab
     *
     * @return array
     */
    public function getFields(){

        return $this->data['fields'];

    }

    /**
     * Returns the slug of this Tab
     *
     * @return string
     */
    public function getSlug(){

        return $this->data['slug'];
    }

    /**
     * Return the title of this tab
     *
     * @return string
     */
    public function getTitle(){

        return $this->data['title'];
    }

    /**
     * Check settings page options: context, priority.
     *
     * @param array $options The settings page options.
     * @return array
     */
    private function parseOptions(array $options) {

        return wp_parse_args( $options, array() );

    }


    /**
     * return the name of these options
     *
     * @return string
     */
    private function getOptionName(){
        return 'settings-'.sanitize_title( $this->data['title'] );
    }


    /**
     * Set the default 'value' property for all fields.
     *
     * @return void
     */
    private function setDefaultValue() {

        $settingsPage = $_GET['page'];
        if( isset( $settingsPage ) ){

            $values = get_option( $settingsPage, array() );

            foreach ( $this->data['fields'] as $field ){

                // Check if saved value
                if( isset( $values[ $field->name] ) ){
                    $value = $values[ $field->name ];
                    $field->properties['defaultValue'] = $value;
                }

            }
        }
    }

}

