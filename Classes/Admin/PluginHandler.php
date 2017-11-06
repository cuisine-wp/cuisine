<?php

    namespace Cuisine\Admin;

    use Cuisine\Database\Migrations\Migrator;

    class PluginHandler{

        /**
         * This runs on plugin activation
         *
         * @return void
         */     
        public function activate(){
            
            //delete the activation trigger:
            delete_option( 'cuisine_activated' );

            //run available migrations:
            ( new Migrator() )->up();
            
            wp_redirect( admin_url('/plugins.php?cuisine_installed=true') );
            exit();
            
        }

        /**
         * This runs on plugin deactivation
         *
         * @return void
         */
        public function deactivate(){
        
        }

    }