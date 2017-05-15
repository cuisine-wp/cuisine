<?php

	namespace Cuisine\Database;

	use Cuisine\Utilities\Fluent;
	use Cuisine\Database\Grammars\MySql;
	use Cuisine\Database\Contracts\QueryProducer;

	class Query extends BaseInterface implements QueryProducer{


		/**
		 * WHERE Clauses 
		 * 
		 * @var Array
		 */
		public $clauses = [];


		/**
		 * Create a new schema record
		 * 
		 * @param string  $table
		 *
		 * @return void
		 */
		public function __construct( $table )
		{
			$this->table = $table;

		}


		/**
		 * Insert a record into the database
		 * 
		 * @param  String $table
		 * @param  Array $data
		 * 
		 * @return Cuisine\Database\Record
		 */
		public function insert( $data )
		{
			$this->addCommand( 'insert', [ 'data' => $data ] );
			return $this;
		}


		/**
		 * Upsert a record into the database
		 * 
		 * @param  String $table
		 * @param  Array $data
		 * 
		 * @return Cuisine\Database\Record
		 */
		public function update( $id, $data )
		{
			$this->where([ 'id' => $id ]);
			$this->addCommand( 'update', [ 'data' => $data ] );
			return $this;
		}


		/**
		 * Delete a record
		 * 
		 * @param  Int $id
		 * 
		 * @return void
		 */
		public function delete( $id )
		{
			$this->where([ 'id' => $id ]);
			$this->addCommand( 'delete' );
			return $this;
		}


		/**
		 * Set where parameters
		 * 
		 * @return Cuisine\Database\Record
		 */
		public function where( $attributes )
		{
			$this->clauses[] = $attributes;
			return $this;
		}


		/**
		 * Return a find command
		 * 
		 * @return Cuisine\Utilities\Fluent;
		 */
		public function find()
		{
			$command = $this->addCommand( 'find' );	
			return $this;
		}

		
		/**
		 * Add a limit to the eventual query
		 * 
		 * @return 
		 */
		public function limit( $limit )
		{
			$this->clauses[] = [ 'limit' => $limit ];	
			return $this;
		}


		/**
		 * Execute the query against the database
		 * 
		 * @param  WPDB $connection
		 * @return void
		 */
		public function run( $connection )
		{
			//set the grammar
			$this->grammar = new MySql( $this, $connection );
			$response = [];

			foreach( $this->toSql() as $key => $statement ) {
				$result = $connection->query( $statement );
				
				if( $this->commands[ $key ]->get( 'name' ) == 'insert' )
					$result = $connection->insert_id;

				$response[] = $result;
			}


			if( sizeof( $response ) == 1 )					
				return $response[0];

			return $response;
		}


		/**
		 * Execute a select query against the databse 
		 * 
		 * @param  WPDB $connection
		 * 
		 * @return array
		 */
		public function results( $connection )
		{
			$this->grammar = new MySql( $this, $connection );
			$sql = $this->toSql();

			$connection->hide_errors();
			$results = $connection->get_results( $sql[0] );
			$connection->show_errors();
			
			return $results;
		}


		/**
		 * Get the prepared SQL statements for the blueprint
		 * 
		 * @return array
		 */
		public function toSql()
		{

			$statements = [];

			foreach( $this->commands as $command ) {

				$method = 'compile'.ucfirst( $command->name );

				if( method_exists( $this->grammar, $method ) ){
					$sql = $this->grammar->$method( $command );
					if( $sql != null ){
						$statements = array_merge( $statements, ( array ) $sql );
					}

				}
			}

			return $statements;
		}

		
	}