<?php

	namespace Cuisine\View;
	
	class Loop{


		/**
		 * Get the id of the current post.
		 * 
		 * @return int The ID of the current post.
		 */
		public static function id(){

			return get_the_ID();

		}
	

		/**
		 * Get the title of the current post.
		 * 
		 * @return string The title of the current post.
		 */
		public static function title(){

			return get_the_title();

		}
	

		/**
		 * Get the content of the current post.
		 *
		 * @return string The content of the current post.
		 */
		public static function content(){

			$content = apply_filters('the_content', get_the_content());
			$content = str_replace(']]>', ']]&gt;', $content);
			return $content;

		}
	

		/**
		 * Get the excerpt of the current post.
		 *
		 * @return string The excerpt of the current post.
		 */
		public static function excerpt(){

			return get_the_excerpt();

		}
	

		/**
		 * Show the content before the more tag
		 * 
		 * @return string ( html )
		 */
		public static function beforeMore(){
	
			global $post;
			return Excerpt::beforeMore( $post->post_content );
	
		}
	
	
		/**
		 * Show the content before the more tag
		 * 
		 * @return string ( html )
		 */
		public static function afterMore(){
	
			global $post;
			return Excerpt::afterMore( $post->post_content );
	
		}


		/**
		 * Get the post thumbnail of the current post.
		 *
		 * @param string|array The size of the current post thumbnail.
		 * @param string|array The attributes of the current post thumbnail.
		 * @return string The thumbnail of the current post.
		 */
		public static function thumbnail($size = null, $attr = null){

			return get_the_post_thumbnail(static::id(), $size, $attr);

		}
	

		/**
		 * Get the post type
		 * 
		 * @return string
		 */
		public static function type(){

			return get_post_type();

		}


		/**
		 * Get the post date
		 * 
		 * @param  string $format date format
		 * @return string
		 */
		public static function date( $format = 'j-M-Y' ){

			return get_the_date( $format );

		}


		/**
		 * Get the permalink of the current post.
		 *
		 * @return string The permalink of the current post.
		 */
		public static function link(){

			return get_permalink();

		}


		/**
		 * Generate a button
		 * 
		 * @param  string $link
		 * @param  string $text
		 * @return string 
		 */
		public static function button( $link, $text, $class = '' ){

			$class = 'button '.$class;

			$html = '<a href="'.$link.'" class="'.$class.'">';
			$html .= $text;
			$html .= '</a>';

			return $html;
		}
	

		/**
		 * Generate a read-more button
		 * 
		 * @param  string $text
		 * @return string
		 */
		public static function readMore( $text = 'Lees meer &raquo;' ){

			$link = static::link();

			return static::button( $link, $text, 'read-more' );

		}


		/**
		 * Return a custom field
		 * 
		 * @param  string $field Field name
		 * @return mixed, returns false if not available.
		 */
		public static function field( $field, $id = false ){

			if( !$id )
				$id = static::id();

			$meta = get_post_meta( $id, $field );

			if( $meta ){

				if( count( $meta ) === 1 )
					return $meta[0];

				return $meta;
			}

			return false;
		}


		/**
		 * Return sections
		 * 
		 * @return mixed, returns false if not available
		 */
		public static function sections(){

			if( function_exists( 'get_sections' ) )
				return get_sections();

			return false;
		}

		/**
		 * Return a single section
		 * 
		 * @param  int $post_id 
		 * @param  int $section_id 
		 * @return mixed, returns false if not available
		 */
		public static function section( $post_id, $section_id ){

			if( function_exists( 'get_section' ) )
				return get_section( $post_id, $section_id );

			return false;
		}


		/**
		 * Return the related posts
		 * 
		 * @return mixed, returns false if not available
		 */
		public static function related(){

			if( function_exists( 'get_related' ) )
				return get_related();

			return false;
			
		}
		

		/**
		 * Get the categories of the current post.
		 *
		 * @param int $id The post ID.
		 * @return array The categories of the current post.
		 */
		public static function category($id = null){

			return get_the_category($id);

		}
	

		/**
		 * Get the tags of the current post.
		 *
		 * @return array The tags of the current post.
		 */
		public static function tags(){

			return get_the_tags();

		}
	

		/**
		 * Get the terms (custom taxonomies) of the current post.
		 *
		 * @param string $taxonomy The custom taxonomy slug.
	     * @see https://codex.wordpress.org/Function_Reference/get_the_terms
		 * @return array|false|\WP_Error
		 */
		public static function terms($taxonomy){

			return get_the_terms(static::id(), $taxonomy);

		}

	}