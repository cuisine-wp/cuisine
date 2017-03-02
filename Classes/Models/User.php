<?php
namespace Cuisine\Models;

use WP_User;

class User extends WP_User {
    /**
     * Check if the user has role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole( $role ) {

        $user = wp_get_current_user();

        return in_array( $role, $user->roles );
    }

    /**
     * Set User role.
     *
     * @param string $role
     * @return \Cuisine\User\User
     */
    public function setRole( $role ) {
        $user = wp_get_current_user();
        $user->set_role($role);
        return $this;
    }


    /**
     * Get the Current User Id
     * 
     * @return int
     */
    public function getId(){

        return get_current_user_id();
        
    }

    /**
     * Check if the user can do a defined capability.
     *
     * @param string $cap
     * @return bool
     */
    public function can( $cap ) {

        $user = wp_get_current_user();
        return current_user_can( $cap );
    
    }


    /**
     * Get an attribute for this user
     * 
     * @param  string $attribute name of the attribute
     * @return mixed (result or false )
     */
    public function get( $attribute ){

        $user = wp_get_current_user();

        //no current user set:
        if( !isset( $user->ID ) || $user->ID == '' )
            return false;

        switch( $attribute ){

            case 'email':

                return $user->data->user_login;

            break;

            case 'username':

                return $user->data->user_login;

            break;

            case 'ID':

                return $user->data->ID;

            break;

            case 'display-name':

                return $user->display_name;

            break;

            default:

                return get_user_meta( $user->data->ID, $attribute, true );

            break;

        }

        return false;
    }


    /**
     * Check if the user is logged in
     * 
     * @return bool
     */
    public function loggedIn(){
        return is_user_logged_in();
    }


    /**
     * Update the user properties.
     *
     * @param array $userdata
     * @return \Cuisine\User\User|\WP_Error
     */
    public function update(array $userdata) {
        $user = wp_get_current_user();    
        $userdata = array_merge( $userdata, array( 'ID' => $user->ID ) );

        $user = wp_update_user($userdata);

        if(is_wp_error($user)) return $user;

        return $this;
    }

} 