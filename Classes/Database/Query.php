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
		 * Return records
		 * 
		 * @return [type] [description]
		 */
		public function find()
		{
			
			return $results;
		}

		

		/**
		 * Execute the blueprint against the database
		 * 
		 * @param  WPDB $connection
		 * @return void
		 */
		public function run( $connection )
		{
			//set the grammar
			$this->grammar = new MySql( $this, $connection );

			foreach( $this->toSql() as $statement ) {

				$connection->query( $statement );
	
			}
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