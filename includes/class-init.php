<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    waj
 * @subpackage waj/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    waj
 * @subpackage waj/includes
 * @author     Your Name <email@example.com>
 */
class WAJ {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WSDS_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $waj    The string used to uniquely identify this plugin.
	 */
	protected $NAME;
	const NAME = 'waj';	
	const VER = '1.0.1';	
        
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $waj    The string used to uniquely identify this plugin.
	 */
	protected $file;
        

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
        
        public static $ADMIN_ROOT;
        public static $ADMIN_URL;
        public static $ADMIN_PARTIALS_ROOT;
        public static $INCLUDES_ROOT;
        public static $PUBLIC_ROOT;
        
        const SLUG = 'maxi-woo-ajax-navigation';        
        

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct($file, $plugin_dir) {

		$this->file = $file;
		//$this->NAME = 'waj';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
                
                self::$ADMIN_URL = plugin_dir_url( $plugin_dir ) . "/". self::SLUG . "/admin/";
                self::$ADMIN_ROOT = $plugin_dir . "/admin/";
                self::$ADMIN_PARTIALS_ROOT = $plugin_dir . "/admin/partials/";

                self::$INCLUDES_ROOT = $plugin_dir . "/includes/";

                self::$PUBLIC_ROOT = plugin_dir_url( $plugin_dir ) . "/". self::SLUG . "/public/";                

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WSDS_Loader. Orchestrates the hooks of the plugin.
	 * - WSDS_i18n. Defines internationalization functionality.
	 * - WSDS_Admin. Defines all hooks for the dashboard.
	 * - WSDS_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-waj-loader.php';

		/**
		 * Functions
		 */		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-waj-functions.php';               
                
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-waj-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-waj-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-waj-admin_pages.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-waj-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-waj-public.php';

		$this->loader = new WSDS_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WSDS_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WSDS_i18n();
		$plugin_i18n->set_domain( $this->get_NAME() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WAJ_Admin( $this->get_NAME(), $this->get_version() );
		$plugin_admin_pages = new WAJ_Admin_Pages( $this->get_NAME() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_pages' );                
                
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );                
                
		// Initialise settings                

		// Register plugin settings
		//$this->loader->add_action( 'admin_init' , $this->settingsClass, 'register_settings' );

		// Add settings link to plugins page
		$this->loader->add_filter( 'plugin_action_links_' . $this->file , 'WAJ_Admin', 'add_settings_link' );
                
	}
        
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WAJ_Public( $this->get_NAME(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_shortcode( 'woo_ajax_nav', $plugin_public, 'products_list' );	

		$this->loader->add_action( 'wp_ajax_nopriv_woo_ajax_nav', $plugin_public, 'AJAX_get' );		
		$this->loader->add_action( 'wp_ajax_woo_ajax_nav', $plugin_public, 'AJAX_get' );		

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_NAME() {
		return self::NAME;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WSDS_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
