<?php

	namespace Cuisine\Admin;

	class FlashMessages{

		/**
		 * Array of notifications to display
		 * 
		 * @var Array
		 */
		protected $notifications;

		
		/**
		 * Types of notifications
		 *
		 * @var Array
		 */
		const TYPES = [
			'success',
			'error',
			'warning'
		];



		/***********************************************/
		/**          Notification Display              */
        /***********************************************/
        
        /**
         * Display the notifications
         *
         * @return void
         */
        public function display()
        {
            if( !empty( $this->notifications ) ){

                foreach( self::TYPES as $type ){

                    if( $this->getNotificationCount( $type ) > 0 )
                        $this->displayNotifications( $type );

                }
            }
        }

		/**
		 * Display Notifications of a certain type
		 *
		 * @param String $type
		 * 
		 * @return String
		 */
		public function displayNotifications( $type )
		{
			$notifications = $this->getNotifications( $type );
			foreach( $notifications as $notification ){

				printf( 
					'<div class="%s"><p>%s</p></div>', 
					esc_attr( 'notice notice-'.$type ), 
					$notification['message']
				); 

			}
		}




		/***********************************************/
		/**          Notification Management           */
		/***********************************************/


		/**
		 * Add a notification
		 * 
		 * @param String $message
		 * @param String $type
		 *
		 * @return void
		 */
		public function add( $message, $type = 'success' )
		{
			$this->notifications[] = [ 'message' => $message, 'type' => $type ];
		}

        /**
         * Add a default message
         * 
         * @param String $message
         *
         * @return void
         */
        public function message( $message )
        {
            $this->add( $message, 'success' );
        }

		/**
		 * Add an error
		 * 
		 * @param String $message
		 *
		 * @return void
		 */
		public function error( $message )
		{
			$this->add( $message, 'error' );
		}

        /**
         * Add a warning
         *
         * @param String 
         * 
         * @return void
         */
        public function warning( $message )
        {
            $this->add( $message, 'warning' );
        }


		/**
		 * Get all notifications 
		 *
		 * @param  String $type
		 * 
		 * @return Array
		 */
		public function getNotifications( $type = null )
		{
			if( is_null( $type ) )
				return $this->notifications;

			$response = [];

			if( !empty( $this->notifications ) ){
				
				foreach( $this->notifications as $notification ){
					if( $notification['type'] == $type )
						$response[] = $notification;
				}
			}

			return $response;
		}


		/**
		 * Get the notification count
		 * 
		 * @param  String $type
		 * 
		 * @return Int
		 */
		public function getNotificationCount( $type = null )
		{
			return count( $this->getNotifications( $type ) );
		}

    }
    