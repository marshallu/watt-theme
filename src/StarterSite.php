<?php

namespace App;

use Timber\Site;
use Timber\Timber;

/**
 * Class StarterSite
 */
class StarterSite extends Site {
	/**
	 * StarterSite constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'smartwp_disable_emojis' ) );
		add_action( 'init', array( $this, 'register_block_json_files' ) );
		add_action( 'init', array( $this, 'register_options_page' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles_and_scripts' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'theme_info_box' ) );
		add_action( 'admin_init', array( $this, 'remove_dashboard_meta' ) );
		add_filter( 'allowed_block_types_all', array( $this, 'allowed_block_types' ), 10, 2 );

		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_filter( 'timber/twig/environment/options', array( $this, 'update_twig_environment_options' ) );

		parent::__construct();
	}

	/**
	 * This is where you can register custom post types.
	 */
	public function register_post_types() {
	}

	/**
	 * This is where you can register custom taxonomies.
	 */
	public function register_taxonomies() {
	}

	/**
	 * This is where you can register styles and scripts.
	 */
	public function register_styles_and_scripts() {
		wp_enqueue_style( 'watt', get_template_directory_uri() . '/css/watt.css', array(), filemtime( get_theme_file_path( '/css/watt.css' ) ), 'all' );
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS.
	}

	/**
	 * Add the theme info box to the Dashboard homepage.
	 *
	 * @return void
	 */
	public function theme_info_box() {
		add_meta_box( 'theme_info', 'WATT Theme Details', array( $this, 'theme_info_box_content' ), 'dashboard', 'side', 'core' );
	}

	/**
	 * Add a metabox to the Dashboard homepage for information on the WordPress theme.
	 *
	 * @return void
	 */
	public function theme_info_box_content() {
		Timber::render( 'admin/theme-info-box.twig' );
	}

	/**
	 * Remove some unneeded widgets from the Dashboard homepage.
	 *
	 * @return void
	 */
	public function remove_dashboard_meta() {
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
		remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );
		remove_meta_box( 'rg_forms_dashboard', 'dashboard', 'side' );
	}

	/**
	 * Disable the emoji's
	 */
	public function smartwp_disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
	}

	/**
	 * Filter function used to remove the tinymce emoji plugin.
	 *
	 * @param array $plugins Plugin list.
	 *
	 * @return array Difference betwen the two arrays
	 */
	public function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}


	/**
	 * Register custom ACF blocks.
	 */
	public function register_block_json_files() {
		$blocks = scandir( dirname( __DIR__ ) . '/blocks/' );
		$blocks = array_values( array_diff( $blocks, array( '..', '.', '.DS_Store', '_base-block' ) ) );

		foreach ( $blocks as $block ) {
			if ( file_exists( dirname( __DIR__ ) . '/blocks/' . $block . '/block.json' ) ) {
				register_block_type( dirname( __DIR__ ) . '/blocks/' . $block . '/block.json' );
			}

			if ( file_exists( dirname( __DIR__ ) . '/blocks/' . $block . '/callback.php' ) ) {
				require_once dirname( __DIR__ ) . '/blocks/' . $block . '/callback.php';
			}
		}
	}

	/**
	 * Disable the default WordPress blocks and enable our custom blocks.
	 *
	 * @param array $allowed_blocks Array of all blocks.
	 * @return array
	 */
	public function allowed_block_types( $allowed_blocks ) {
		$allowed_blocks = array(
			'acf/basic',
			'core/freeform',
		);

		return $allowed_blocks;
	}

	/**
	 * Register the theme options page.
	 */
	public function register_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				array(
					'page_title' => 'Theme Settings',
					'menu_title' => 'Theme Settings',
					'menu_slug'  => 'theme-settings',
					'capability' => 'activate_plugins',
					'redirect'   => false,
				)
			);
		}
	}

	/**
	 * This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['foo']  = 'bar';
		$context['menu'] = Timber::get_menu( 'site_menu' );
		$context['site'] = $this;

		return $context;
	}

	/**
	 * This is where you set the default WordPress theme settings.
	 */
	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );

		register_nav_menus(
			array(
				'site_menu' => 'Site Menu',
			)
		);
	}

	/**
	 * Return the srcset for images.
	 *
	 * @param string $id The image ID.
	 *
	 * @return false|string
	 */
	public function srcset( $id ) {
		return wp_get_attachment_image_srcset( $id, 'full' );
	}

	/**
	 * This is where you can add your own functions to twig.
	 *
	 * @param Twig\Environment $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addFilter( new \Twig\TwigFilter( 'srcset', array( $this, 'srcset' ) ) );
		return $twig;
	}

	/**
	 * Updates Twig environment options.
	 *
	 * @link https://twig.symfony.com/doc/2.x/api.html#environment-options
	 *
	 * @param array $options An array of environment options.
	 *
	 * @return array
	 */
	public function update_twig_environment_options( $options ) {
		// $options['autoescape'] = true;

		return $options;
	}
}
