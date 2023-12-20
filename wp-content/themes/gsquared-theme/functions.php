<?php
/**
 * G Squared functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package G_Squared
 */

if ( ! defined( 'GSQUARED_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'GSQUARED_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function gsquared_theme_setup() {
	/*
	* Make theme available for translation.
	* Translations can be filed in the /languages/ directory.
	* If you're building a theme based on G Squared, use a find and replace
	* to change 'gsquared-theme' to the name of your theme in all the template files.
	*/
	load_theme_textdomain( 'gsquared-theme', get_template_directory() . '/languages' );

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

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'gsquared-theme' ),
		)
	);

	/*
	* Switch default core markup for search form, comment form, and comments
	* to output valid HTML5.
	*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'gsquared_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gsquared_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'gsquared_theme_content_width', 1640 );
}
add_action( 'after_setup_theme', 'gsquared_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function gsquared_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'gsquared-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'gsquared-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'gsquared_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function gsquared_theme_scripts() {
	wp_enqueue_style( 'gsquared-theme-style', get_stylesheet_uri(), array(), GSQUARED_VERSION );
	wp_style_add_data( 'gsquared-theme-style', 'rtl', 'replace' );

	wp_enqueue_style( 'gsquared-theme-custom-style', get_template_directory_uri() . '/assets/css/style.css', array(), GSQUARED_VERSION );

	wp_enqueue_script( 'gsquared-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), GSQUARED_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gsquared_theme_scripts' );

/**
 * Add a custom body class based on the selected page template.
 *
 * @param array $classes An array of body classes.
 * @return array $classes Modified array of body classes.
 */
function add_body_class_based_on_template( $classes ) {
	if ( is_page() ) {
		$template = get_page_template();
		if ( 'home-page.php' === basename( $template ) ) {
			$classes[] = 'home-page';
		}
	}
	return $classes;
}
add_filter( 'body_class', 'add_body_class_based_on_template' );

/**
 * Enqueues a custom CSS file for the WordPress admin dashboard.
 */
function custom_dashboard_css() {
	wp_enqueue_style( 'dashboard-icons', get_stylesheet_directory_uri() . '/assets/css/admin-dashboard.css', false, time(), 'all' );
}
add_action( 'admin_print_styles', 'custom_dashboard_css' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	include get_template_directory() . '/inc/jetpack.php';
}
