<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    waj
 * @subpackage waj/admin/partials
 */
?>

<div class="wrap" id="waj_plugin_settings">

    <?php 
        if ( $save ) {
            echo '<div id="setting-error-settings_updated" class="updated settings-error">
                <p>
                    <strong>'. __( 'Settings saved.' , 'waj' ) .'</strong>
                </p>
             </div>';
        }
    ?>    
    
    <h2><?php _e('Plugin description / settings', 'waj') ?></h2>
    
    <p>Shortcode:<code>[woo_ajax_nav columns="3" orderby="title" per_page="3" product_cat="" ids="" skus=""]</code></p>

    <?php $classSettings->render_page($save); ?>
    
    <?php include '_sidebar.php' ?>
    
</div>