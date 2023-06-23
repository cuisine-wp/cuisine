<?php

	namespace Cuisine\Database;

	use Cuisine\Utilities\Collection;

	class Record{


		/**
		 * Database connection 
		 * 
		 * @var WPDB instance
		 */
		protected $connection;


		/**
		 * Query we're building
		 * 
		 * @var Cuisine\Database\Query
		 */
		protected $query;


		/**
		 * Create a new database schema manager
		 *
		 * @return  void
		 */
		public function __construct()
		{
			global $wpdb;
			$this->connection = $wpdb;
		}


		/**
		 * Sets the table
		 * 
		 * @param  string $table
		 * 
		 * @return Cuisine\Database\Record
		 */
		public function table( $table)
		{
			$this->query =  $this->createQuery( $table );
			return $this;
		}


		/**********************************************/
		/********  WRITE & DELETE
		/**********************************************/

		/**
		 * Insert or update a record, based on the passed on data
		 * 
		 * @param  String $table 
		 * @param  Array $data  
		 * 
		 * @return void
		 */
		public function insertOrUpdate( $table, $data )
		{
			if( isset( $data['id'] ) && !is_null( $data['id'] ) && $data['id'] != '' ){

				$id = $data['id'];
				unset( $data['id'] );
                $data = esc_sql( $data );
				return $this->update( $table, $id, $data );
			}

            $data = esc_sql( $data );
			return $this->insert( $table, $data );
		}

		/**
		 * Insert a record
		 *
		 * @param  String $table
		 * @param  Array $data
		 * 
		 * @return void
		 */
		public function insert( $table, $data )
		{
            $data = esc_sql( $data );
			$query = $this->createQuery( $table );
			$query->insert( $data );

			return $this->run( $query );	
		}


		/**
		 * Update a record
		 * 
		 * @param  string $table
		 * @param  int $id
		 * @param  Array $data
		 * 
		 * @return void
		 */
		public function update( $table, $id, $data )
		{
            $data = esc_sql( $data );
			$query = $this->createQuery( $table );
			$query->update( $id, $data );

			return $this->run( $query );
		}


		/**
		 * Drop a record
		 * 
		 * @param  string $table
		 * @param  int $id
		 * 
		 * @return void
		 */
		public function delete( $table, $id )
		{
			$query = $this->createQuery( $table );
			$query->delete( $id );

			$this->run( $query );
		}


		/**********************************************/
		/********  FIND
		/**********************************************/

		/**
		 * Find a table
		 * 
		 * @param  string $table
		 * 
		 * @return Cuisine\Database\Record
		 */
		public function find( $table )
		{
			$this->query =  $this->createQuery( $table );
			$this->query->find();

			return $this;
		}


		/**
		 * Add where clauses to a qu$this->query->find();ery
		 * 
		 * @param  Array $data
		 * 
		 * @return Cuisine\Database\Record
		 */
		public function where( Array $data )
		{
			$this->query->where( $data );
			return $this;
		}


		/**
		 * Retrieve the first result
		 * 
		 * @return Object
		 */
		public function first()
		{
			$this->query->limit( 1 );
			$results = $this->results();

			if( !is_null( $results ) && !$results->isEmpty() )
				return $results->first();
			

			return null;
		}


		/**
		 * Get the results
		 * 
		 * @return Array
		 */
		public function results()
		{
			$results = $this->query->results( $this->connection );
			$results = new Collection( $results );
			if( !$results->isEmpty() )
				return $results;

			return null;
		}



		/**
	     * Execute the query to run / modify the table.
	     *
	     * @param  \Cuisine\Database\Query  $query
	     * 
	     * @return void
	     */
	    protected function run( Query $query )
	    {
	        return $query->run( $this->connection );
	    }



		 /**
	     * Create a new command set with a Closure
	     *
	     * @param string $table
	     *
	     * @return \Cuisine\Database\Query
	     */
	    protected function createQuery( $table )
	    {
	    	return new Query( $table );
	    }

	}
