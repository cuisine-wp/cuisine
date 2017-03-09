<?php

	namespace Cuisine\Database\Migrations;

	use Cuisine\Wrappers\Schema;
	use Cuisine\Database\Blueprint;
	use Cuisine\Database\Contracts\Migration as MigrationContract;

	class Setup extends Migration implements MigrationContract{

		/**
		 * Setup the migrations table
		 * 
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'migrations', function( Blueprint $table ){
				$table->increments( 'id' )->unique();
				$table->string( 'name' );
				$table->timestamp( 'created' )->useCurrent();
			});
		}


		public function down()
		{
			Schema::drop( 'migrations' );
		}

	}

	\Cuisine\Database\Migrations\Setup::getInstance();