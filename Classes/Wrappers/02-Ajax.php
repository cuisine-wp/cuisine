<?php

	namespace Cuisine\Wrappers;

	class AjaxInstance extends StaticInstance{

		/**
		 * WordPress doesn't keep the post-global around, so we do it this way
		 *
		 * @return void
		 */
		public function setPostGlobal(){
			
			global $post;
			if( !isset( $post ) ){
				$GLOBALS['post'] = new stdClass();
				$GLOBALS['post']->ID = $_POST['post_id'];

			} 
		}


	}
