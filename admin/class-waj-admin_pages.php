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
class WAJ_Admin_Pages {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;
	
	public function __construct( $name ) {
            $this->name = $name;
	}
        
        /**
	 * Settings
	 * @return void
	 */           
        public function page_settings () {
            $classSettings = new WAJ_Settings(WAJ::NAME);
            $save = false;
            if ( isset($_GET['action']) && $_GET['action'] == 'save' && !empty($_POST) ) {
                $classSettings->save_settings();
                $save = true;
            }
            include 'partials/page_settings.php';
        }

}
