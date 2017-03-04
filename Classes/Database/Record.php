<?php

	namespace Cuisine\Database;

	use Cuisine\Wrappers\Schema as Table;
	use Cuisine\Utilities\Fluent;

	class Record{

		/**
		 * Table we're manipulating
		 * 
		 * @var string
		 */
		protected $table;

		/**
		 * Rudamentary query
		 * 
		 * @var Fluent
		 */
		protected $query;

		/**
		 * Find a record or a bunch of records
		 * 
		 * @return Object | null
		 */
		public function table( $table )
		{
			$this->table = $this->wrap( $table );
			return $this;
		}


		public function where( $query )
		{
			$this->query = new Fluent( $query );
			return $this;
		}

		/**
		 * get the records collected
		 * 
		 * @return 
		 */
		public function find()
		{
			//$query = Sort::appendValues( $this->query, "'" );
			//$query = implode( "` = '", $query )
		}


		/**
		 * Insert a record into the database
		 * 
		 * @param  String $table
		 * @param  Array $data
		 * 
		 * @return Object
		 */
		public function insert( $table, $data )
		{
			return $this->inserOrUpsert( $table, $data );			
		}


		/**
		 * Upsert a record into the database
		 * 
		 * @param  String $table
		 * @param  Array $data
		 * 
		 * @return Object
		 */
		public function upsert( $table, $data )
		{
			return $this->insertOrUpsert( $table, $data );
		}


		/**
		 * Insert or upsert a record into the database
		 * 
		 * @param  String $table
		 * @param  Array $data 
		 * 
		 * @return Object
		 */
		public function insertOrUpsert( $table, $data )
		{
			$blueprint = Table::getBlueprint( $table );
			
			if( $this->validate( $blueprint, $data ) ){

			}
		}


		/**
		 * Drop a record
		 * 
		 * @param  string $table
		 * @param  int $id
		 * 
		 * @return void
		 */
		public function drop( $table, $id )
		{
				
		}


		/**
		 * Wrap a string in DB quotes
		 * 
		 * @param  String $string
		 * 
		 * @return String
		 */
		protected function wrap( $string )
		{
			return "`{$string}`";
		}
	}