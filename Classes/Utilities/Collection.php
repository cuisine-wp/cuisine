<?php

	namespace Cuisine\Utilities;


	class Collection{


		/**
		 * Array of all items
		 * 
		 * @var Array
		 */
		protected $items;

		/**
		 * Array of all objects
		 *
		 * @var Array
		 */
		protected $objects;


		/**
		 * Sets the return value for this collection
		 * 
		 * @var string
		 */
		protected $returnValue;



		/**
		 * Constructor
		 * 
		 * @param int $postId
		 */
		public function __construct( $objects )
		{
			$this->objects = $objects;
			$this->items = $this->getItems();
			$this->returnValue = 'objects';
		}




		/*=============================================================*/
		/**             Getters                                        */
		/*=============================================================*/

		/**
		 * Returns all objects
		 * 
		 * @return Array
		 */
		public function all()
		{
			return $this->getReturnValue();
		}


		/**
		 * Get a specific key
		 * 
		 * @param  String $key
		 * 
		 * @return Array | Object
		 */
		public function get( $key, $default = null )
		{
			$values = $this->getReturnValue();

			if( is_array( $values ) && isset( $values[ $key ] ) )
				return $values[ $key ];

			return $default;
		}

		/**
		 * Returns the first object in the array
		 * 
		 * @return Array | Object
		 */
		public function first()
		{
			$values = $this->getReturnValue();

			if( is_array( $values ) )
				$values = array_values( $values );

			return $values[ 0 ];
		}


		/**
		 * Returns wether this collection is empty
		 * 
		 * @return bool
		 */
		public function isEmpty()
		{
			return ( empty( $this->items ) );	
		}


		/**
		 * Get the amount of items in this collection
		 * 
		 * @return int
		 */
		public function count(){
			return count( $this->items );
		}


		/*=============================================================*/
		/**             Return data                                    */
		/*=============================================================*/


		/**
		 * Checks which collection to return
		 * 
		 * @return Array of objects | Array of Arrays
		 */
		public function getReturnValue()
		{
			if( $this->returnValue == 'array' )
				return $this->items;

			return $this->objects;
		}


		/**
		 * Set the return value for this collection
		 * 
		 * @return ChefSections\Collections\Collection
		 */
		public function toArray()
		{
			$this->returnValue = 'array';
			return $this;
		}

		/**
		 * Set the method for this collection
		 * 
		 * @return ChefSections\Collections\Collection
		 */
		public function toObjects()
		{
			$this->returnValue = 'objects';
			return $this;
		}


		/**
		 * Returns this collection as a JSON
		 * 
		 * @return string (json)
		 */
		public function toJson()
		{
			return json_encode( array_values( $this->toArray()->all() ) );
		}


		/*=============================================================*/
		/**             Set class data:                                */
		/*=============================================================*/


		/**
		 * Returns all objects as items
		 * 
		 * @return Array
		 */
		public function getItems()
		{
			$result = [];
			foreach( $this->objects as $key => $object ){
				if( method_exists( $object, 'toArray' ) ){
                    $result[ $key ] = $object->toArray();
                }else{
                    $result[ $key ] = ( array ) $object;
                }
			}

			return $result;
		}


		/**
		 * Returns an array of all objects
		 * 
		 * @return Array
		 */
		public function getObjects()
		{
			return $this->objects;				
		}

	}