<?php

	namespace Cuisine\Front;

	use Cuisine\Wrappers\StaticInstance;
	
	/**
	 * WordPress has a habit of throwing bad code at us, let's not let it, shall we?
	 */
	class CleanCode extends StaticInstance {
	
	
	
		/**
		 * Init events & vars
		 */
		function __construct(){
	
			$this->events();
	
			$this->setRelativeUrls();
		}
	
	
		/**
		 * Set the events for this request
		 * 
		 * @return void
		 */
		private function events(){
	
			add_action( 'init', array( &$this, 'cleanHead' ) );
	
			add_filter( 'body_class', array( &$this, 'bodyClass' ) );
	
			add_filter( 'embed_oembed_html', array( &$this, 'embedWrap' ) );
	
			add_filter( 'get_avatar', array( &$this, 'removeSelfClosingTags' ) ); // <img />
	
			add_filter( 'comment_id_fields', array( &$this, 'removeSelfClosingTags' ) ); // <	input />
	
			add_filter( 'post_thumbnail_html', array( &$this, 'removeSelfClosingTags' ) ); // <	img />
	
		}
	
		/**
		 * Set the filters for relative urls
		 *
		 * @return void
		 */
		private function setRelativeUrls(){
	
			add_filter( 'bloginfo_url', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'the_permalink', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'wp_list_pages', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'wp_list_categories', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'wp_get_attachment_url', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'the_content_more_link', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'the_tags', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'get_pagenum_link', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'get_comment_link', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'month_link', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'day_link', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'year_link', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'term_link', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'the_author_posts_link', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'script_loader_src', array( &$this, 'toRelativeUrl' ) );
			add_filter( 'style_loader_src', array( &$this, 'toRelativeUrl' ) );
	
		}
	
	
		/**
		 * Generate a relative url out of an absolute one
		 * 
		 * @return string
		 */
		public function toRelativeUrl( $input ){
			
			$url = parse_url($input);
			
			if( !isset( $url['host'] ) || !isset( $url['path'] ) ) {
	
			  return $input;
			
			}
			
			$site_url = parse_url(network_site_url());  // falls back to site_url
	
			if( !isset( $url['scheme'] ) ) {
			  $url['scheme'] = $site_url['scheme'];
			}
			
			$hosts_match = $site_url['host'] === $url['host'];
			$schemes_match = $site_url['scheme'] === $url['scheme'];
			$ports_exist = isset($site_url['port']) && isset($url['port']);
			$ports_match = ($ports_exist) ? $site_url['port'] === $url['port'] : true;
	 
			if( $hosts_match && $schemes_match && $ports_match ) {
			  return wp_make_link_relative( $input );
			}
			
			return $input;
		}
	
	
	
		/**
		 * Clean WP_Head
		 * 
		 * @return void
		 */
		public function cleanHead(){
	
			// Originally from http://wpengineer.com/1438/wordpress-header/
			remove_action('wp_head', 'feed_links_extra', 3);
			
			add_action('wp_head', 'ob_start', 1, 0);
	
			add_action('wp_head', function () {
			  $pattern = '/.*' . preg_quote(esc_url(get_feed_link('comments_' . get_default_feed()	)), '/') . '.*[\r\n]+/';
			  echo preg_replace($pattern, '', ob_get_clean());
			}, 3, 0);
			
			remove_action('wp_head', 'rsd_link');
			
			remove_action('wp_head', 'wlwmanifest_link');
			
			remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
			
			remove_action('wp_head', 'wp_generator');
			
			remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	
			add_filter('the_generator', '__return_false');
				
			add_filter('use_default_gallery_style', '__return_false');
	
	
			//all the emoji crap:
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('admin_print_scripts', 'print_emoji_detection_script');
			remove_action('wp_print_styles', 'print_emoji_styles');
			remove_action('admin_print_styles', 'print_emoji_styles');
			remove_filter('the_content_feed', 'wp_staticize_emoji');
			remove_filter('comment_text_rss', 'wp_staticize_emoji');
			remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
		}
	
		/**
		 * Remove crap from the body class
		 * 
		 * @return array
		 */
		public function bodyClass( $classes ){
			
			// Add post/page slug if not present
			if( is_single() || is_page() && !is_front_page() ){
				
				if( !in_array( basename( get_permalink() ), $classes ) ){
					
					$classes[] = basename( get_permalink() );
				
				}
			}
	
			// Remove unnecessary classes
			$home_id_class = 'page-id-' . get_option( 'page_on_front' );
			$remove_classes = array(
			    'page-template-default',
			    $home_id_class
			);
			

			$classes = array_diff( $classes, $remove_classes );
			return $classes;

		}
	
	
	
	
		/**
		 * Wrap embedded media as suggested by Readability
		 *
		 * @link https://gist.github.com/965956
		 * @link http://www.readability.com/publishers/guidelines#publisher
		 * @return string
		 */
		public function embedWrap( $cache ) {
		  return '<div class="entry-content-asset">' . $cache . '</div>';
		}
	
	
	
		/**
		 * Remove unnecessary self-closing tags
		 *
		 * @return altered string
		 */
		public function removeSelfClosingTags( $input ) {
		  return str_replace(' />', '>', $input);
		}
	
	
		
	}
	
	
	if( !is_admin() )
		\Cuisine\Front\CleanCode::getInstance();	