<?php 

	namespace Cuisine\Database\Contracts;

	interface Migration{

		public function up();
		public function down();
		
	}