<?php

	namespace Cuisine\Database;

	use Cuisine\Utilities\Fluent;
	use Cuisine\Utilities\Sort;

	class Grammar{


		/**
		 * The blueprint instance of this object
		 * 
		 * @var Cuisine\Database\Blueprint;
		 */
		protected $blueprint;


		/**
		 * Database connection
		 * 
		 * @var WPDB instance
		 */
		protected $connection;


		/**
	     * The possible column modifiers.
	     *
	     * @var array
	     */
	    protected $modifiers = [
	        'unsigned', 'charset', 'collate', 'nullable',
	        'default', 'increment', 'comment'
	    ];

 		/**
	     * The possible column serials.
	     *
	     * @var array
	     */
	    protected $serials = ['bigInteger', 'integer', 'mediumInteger', 'smallInteger', 'tinyInteger'];
		

		/**
		 * Constructor for the grammar class
		 * 
		 * @param Blueprint $blueprint
		 */
		public function __construct( Blueprint $blueprint, $connection )
		{
			$this->blueprint = $blueprint;	
			$this->connection = $connection;
		}


		/**
		 * Returns the table name
		 * 
		 * @return string
		 */
		public function getTable()
		{
			return $this->wrap( $this->connection->prefix . $this->blueprint->table );
		}

		/**
		 * Wrap this string in sepcial quotes
		 * 
		 * @param  String $string 
		 * 
		 * @return String
		 */
		public function wrap( $string )
		{
			return "`{$string}`";	
		}
		

		/**
		 * Compile a create table command 
		 * 
		 * @param  Fluent $command
		 * 
		 * @return Array
		 */
		public function compileCreate( Fluent $command )
		{
			$columns = implode( ', ', $this->getColumns() );
			$table = $this->getTable();
			
			return "CREATE TABLE $table ( $columns )";
		}


		/**
		 * Compile an add column
		 * 
		 * @param  Fluent $command
		 * 
		 * @return Array
		 */
		public function compileAdd( Fluent $command )
		{
			$table = $this->getTable();
			$columns = Sort::prependValues( $this->getColumns(), 'ADD ' );
			$columns = implode( ', ', $columns );

			return "ALTER TABLE $table $columns";
		}


		/**
		 * Compile a drop table command
		 *
		 * @param  Fluent $command
		 * 
		 * @return Array
		 */
		public function compileDrop( Fluent $command )
		{
			$table = $this->getTable();
			return "DROP TABLE $table";
		}


		/**
		 * Compile a rename table command
		 *
		 * @param  Fluent $command
		 * 
		 * @return Array
		 */
		public function compileRename( Fluent $command )
		{
			 $from = $this->tableName();

        	return "RENAME TABLE {$from} TO ".$this->wrap( $command->to );	
		}

		/**
		 * Compile a drop column command
		 *
		 * @param  Fluent $command
		 * 
		 * @return Array
		 */
		public function compileDropColumn( Fluent $command )
		{
			$columns = Sort::prependValues( $command->columns, 'DROP `' );
			$columns = Sort::appendValues( $columns, '`' );
        	$table = $this->getTable();

        	return "ALTER TABLE $table ".implode( ', ', $columns );	
		}


		/**
		 * Compile an inster command
		 * 
		 * @param  Fluent $command
		 * 
		 * @return void
		 */
		public function compileInsert( Fluent $command )
		{	
			$table = $this->getTable(); 
			$columns = Sort::prependValues( $command->columns, '`' );
			$columns = Sort::appendValues( $columns, '`' );
			$columns = implode( ', ', $columns );

			return "INSERT INTO $table ( $columns )";
		}


		/**
		 * Compile loose columns
		 * 
		 * @return array
		 */
		public function getColumns()
		{
			$columns = [];

			foreach( $this->blueprint->columns as $column ){

				$name = $this->wrap( $column->name );
				$sql = "{$name} {$this->getType( $column )}";

				$columns[] = $this->addModifiers( $sql, $column );
			}
			
			return $columns;
		}


		/**
		 * Returns the type of column in correct MySQL syntax
		 * 
		 * @return string
		 */
		public function getType( Fluent $column )
		{
			switch( strtolower( $column->type ) ){

				case 'char':
					return "char({$column->length})";
					break;

				case 'string':
					return "varchar({$column->length})";	
					break;

				case 'biginteger':
				case 'integer':
				case 'mediuminteger':
				case 'tinyinteger':
				case 'smallinteger':
					return str_replace( 'integer', 'int', $column->type );
					break;

				case 'float':
				case 'double':

					if( $column->total && $column->place )
						return "double( {$column->total}, {$column->places})";

					return 'double';
					break;

				case 'decimal':
					return "decimal({$column->total}, {$column->places})";
					break;

				case 'boolean':
				case 'bool':
					return 'tinyint(1';
					break;

				case 'timestamp':

					if( $column->useCurrent )
						return 'timestamp default CURRENT_TIMESTAMP';

					return 'timestamp';
					break;

				case 'binary':
					return 'blob';
					break;


				default:
					return strtolower( $column->type );
					break;
			}
		}


		/**
		 * Add modifiers to the sql rules
		 * 
		 * @param string $sql
		 * @param Fluent $column
		 */
		public function addModifiers( $sql, Fluent $column )
		{
			foreach ($this->modifiers as $modifier) {

				switch( $modifier ){

					case 'unsigned':
						
						if( $column->unsigned )
							$sql .= ' UNSIGNED';

						break;

					case 'charset':

						if( !is_null( $column->charset ) ){
							$set = $this->wrap( $column->charset );
							$sql .= " CHARACTER SET $set";
						}

						break;

					case 'collate':

						if( !is_null( $column->collation ) ){
							$collation = $this->wrap( $column->collation );
							$sql .= " COLLATE $collation";
						}
						
						break;

					case 'nullable':

						$sql .= ( $column->nullable ? ' NULL' : ' NOT NULL' );
						break;

					case 'default':

						if( !is_null( $column->default ) )
							$sql .= ' DEFAULT '.$this->getDefaultValue( $column->default );
						
						break;

					case 'increment':
						
						if( in_array( $column->type, $this->serials ) && $column->autoIncrement )
							$sql .= ' AUTO_INCREMENT PRIMARY KEY';

						break;
				}
        	}

        	return $sql;
		}


		/**
		 * Returns the default value to a column
		 * 
		 * @param  string $value
		 * 
		 * @return string|null
		 */
		public function getDefaultValue( $value )
		{
			if( is_bool( $value ) ) {
            	return "'".(int) $value."'";
			}

        	return "'".strval($value)."'";
		}

	}