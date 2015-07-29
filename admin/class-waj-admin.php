<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    waj
 * @subpackage waj/includes
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    waj
 * @subpackage waj/admin
 * @author     Your Name <email@example.com>
 */
class WAJ_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

        
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/waj-admin.css', array(), $this->version, 'all' );
                wp_enqueue_style( 'wp-color-picker' );
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/*wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/waj-admin.js', array( 'jquery',  ), $this->version, false );
                
                // прописываем переменные
                $output_data = array(
                    'wp_lang' => get_bloginfo( 'language' ),
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                );                
                wp_localize_script( $this->name, 'waj', $output_data );  */       
                
                wp_enqueue_script( 'wp-color-picker' );

                // We're including the WP media scripts here because they're needed for the image upload field
                // If you're not including an image upload then you can leave this function call out                
                wp_enqueue_media();

                wp_register_script( 'waj-admin-seetings-js',  plugin_dir_url( __FILE__ ) . 'js/waj-settings.js', array( 'wp-color-picker' , 'jquery' ), '1.0.0' );
                wp_enqueue_script( 'waj-admin-seetings-js' );

	}       
        
        
	/**
	 * Register the admin pages
	 *
	 * @since    1.0.0
	 */
	public function admin_pages() {
                $admin_pages = new WAJ_Admin_Pages($this->name);
                //global $submenu;
                //$this->menu_page_id['waj'] = 
                add_submenu_page( 'options-general.php',__( 'Woo Ajax Nav', 'waj' ),  __( 'Woo Ajax Nav', 'waj' ), 'edit_pages', 'waj', array( $admin_pages, 'page_settings' ));
                //$submenu['waj'][0][0] = __( 'Woo Ajax Nav', 'waj' );
	}
        
        /**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=waj">' . __( 'Settings', 'waj' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}        


}
