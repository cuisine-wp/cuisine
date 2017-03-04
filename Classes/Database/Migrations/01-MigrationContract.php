<?php 

	namespace Cuisine\Database\Migrations;

	interface MigrationContract{

		public function up();
		public function down();
		
	}