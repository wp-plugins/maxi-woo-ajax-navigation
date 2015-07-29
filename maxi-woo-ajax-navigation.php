<?php

/**
 *
 * @since             1.0.0
 * @package           waj
 *
 * @wordpress-plugin
 * Plugin Name:       Maxi Woo Ajax Navigation
 * Description:       Woocommerce products list with Ajax navigation, category filter and order changing.
 * Version:           1.0.1
 * Author:            Maxim Kaminsky
 * Author URI:        http://wp-vote.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       waj
 * Domain Path:       /languages
 
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
//require_once plugin_dir_path( __FILE__ ) . 'includes/class-waj-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
//require_once plugin_dir_path( __FILE__ ) . 'includes/class-waj-deactivator.php';

/** This action is documented in includes/class-waj-activator.php */
//register_activation_hook( __FILE__, array( 'WAJ_Activator', 'activate' ) );

/** This action is documented in includes/class-waj-deactivator.php */
//register_deactivation_hook( __FILE__, array( 'WAJ_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-init.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_WAJ() {

	$plugin = new waj( plugin_basename( __FILE__ ), dirname(__FILE__) );
	$plugin->run();

}
run_WAJ();
