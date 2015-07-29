<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    waj
 * @subpackage waj/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    waj
 * @subpackage waj/admin
 * @author     Your Name <email@example.com>
 */
class WAJ_Public {

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
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

            $this->name = $name;
            $this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

            wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/waj-public.min.css', array(), WAJ::VER, 'all' );

            wp_add_inline_style($this->name, 
                '#waj_pagination a { color: ' . WAJ_Functions::check_color(WAJ_Settings::get_setting('color') , '#7899AE') . ' !important; } ' .
                '#waj_pagination a:hover{ color: ' . WAJ_Functions::check_color(WAJ_Settings::get_setting('active_color'), '#faa700') . ' !important; } ' .
                '#waj_pagination .disabled, #waj_pagination .inactive{ color:' . WAJ_Functions::check_color(WAJ_Settings::get_setting('inactive_color'), '#C4C4C4') . '!important; } ' .
                '#waj_pagination { background: ' . WAJ_Functions::check_color(WAJ_Settings::get_setting('pagination_backgroud_color'), '#ebebeb') . ' !important; } '
            );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

            wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/waj-public.js', array( 'jquery' ), WAJ::VER, true );

            // прописываем переменные
            $data = array(
                "ajax_url" => admin_url('admin-ajax.php'),
            );
            // выводим этим пременные
            wp_localize_script( $this->name, 'WAJ', $data );
	}
	
	/**
	 * Show products list - shortcode and AJAX function
	 *
	 * @since    1.0.0
	 */
	public function products_list($atts) {		

            global $woocommerce_loop;

            //if ( empty( $atts ) ) return '';

            $atts = shortcode_atts( array(
                'columns' 	=> '3',
                'orderby'   => 'title',
                'per_page'   => '3',
                'order'     => 'asc',
                //'paged'     => '-1',
                'product_cat' => '',
                'ids'     => '',
                'skus'    => ''                    
            ), $atts );

            // these two line will do pagination
            //if ( $atts['paged'] < 0 ) {
            $paged = ( isset($_GET['paged']) ) ? (int)$_GET['paged'] : 1;


            $offset = ( $atts['per_page'] * $paged ) - $atts['per_page'];               

            if ( defined('DOING_AJAX') && DOING_AJAX == true ) {
                $ordering_args = WC()->query->get_catalog_ordering_args( '', '' );
            } else {
                $ordering_args['orderby'] = $atts['orderby'];
                $ordering_args['order'] = $atts['order'];
            }
            $meta_query = WC()->query->get_meta_query();                     

            $args = array(
                'paged'				=> $paged,		
                'post_type'			=> 'product',
                'post_status' 			=> 'publish',
                'ignore_sticky_posts'           => 1, 
                'orderby' 			=> $ordering_args['orderby'],
                'order' 			=> $ordering_args['order'],
                'offset' 			=> $offset,			
                'posts_per_page' 		=> $atts['per_page'],
                'product_cat'                   => $atts['product_cat'],
                'meta_query' 			=> $meta_query
                /*'meta_query' 			=> array(
                        array(
                                'key' 		=> '_visibility',
                                'value' 	=> array('catalog', 'visible'),
                                'compare' 	=> 'IN'
                        )
                )*/
            );

            if ( isset( $ordering_args['meta_key'] ) ) {
                $args['meta_key'] = $ordering_args['meta_key'];
            }                

            if ( ! empty( $atts['skus'] ) ) {
                $skus = explode( ',', $atts['skus'] );
                $skus = array_map( 'trim', $skus );
                $args['meta_query'][] = array(
                        'key' 		=> '_sku',
                        'value' 	=> $skus,
                        'compare' 	=> 'IN'
                );
            }

            if ( ! empty( $atts['ids'] ) ) {
                $ids = explode( ',', $atts['ids'] );
                $ids = array_map( 'trim', $ids );
                $args['post__in'] = $ids;
            }

            ob_start();

            global $wp_query;
            $wp_query = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

            $atts['columns'] = absint( $atts['columns'] );                
            $woocommerce_loop['columns'] = $atts['columns'];

            if ( $wp_query->have_posts() ) : ?>

                <?php woocommerce_product_loop_start(); ?>

                        <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

                                <?php wc_get_template_part( 'content', 'product' ); ?>

                        <?php endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>

            <?php endif;		

            $this->pagination($wp_query->max_num_pages, $paged, 1, $atts['product_cat']);

            wp_reset_postdata();

            // Remove ordering query arguments
            WC()->query->remove_ordering_args();                

            //OLD MAX
            $cats = $this->output_dropdowns( array('orderby'=>"order", 'hierarchical'=>"0", 'hide_empty'=>"0", 'show_uncategorized'=>"0"), $atts['product_cat'] );

            //OLD MAX		
            return '<div class="woo_ajax_nav"><div id="woo_categories">' . $cats. '</div>'.
                    '<div class="woocommerce headway_woo columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div></div>';
	}	
	
	/**
     * Show thanks page
     *
     * @since    1.0.0
     */
    public function pagination($pages = '', $paged = 1, $paginate_limit = 1, $product_cat = '') {
        //$showitems = ($range * 2)+1;  
        //global $paged;
        if (empty($paged)) {
            $paged = 1;
        }        

        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }

        if (1 != $pages) :
            echo "<div id=\"waj_pagination\"><table><tr>";
            echo '<td>';
            if ($paged > 1) {
                echo "<a href='#' class='prev iwaj' onclick='waj_get_page(" . ($paged - 1) . ",\"" . $product_cat . "\"); return false;'> " . WAJ_Settings::get_setting('prev_text', 'Previous') . "</a>";
            } else {
                echo "<span class='disabled prev iwaj'> " . WAJ_Settings::get_setting('prev_text', 'Previous') . "</span>";
            }
            echo '</td><td>';

            $dotshow = true;
            // walk through the list of pages 
            for ($i = 1; $i <= $pages; $i ++) {
                // If first or last page or the page number falls 
                // within the pagination limit 
                // generate the links for these pages 
                if ($paged == 1 || $paged == $pages) {
                    $coeff = 1;
                } else {
                    $coeff = 0;
                }
                //var_dump($coeff);

                if ($i == 1 || $i == $pages ||
                        ($i >= $paged - ($paginate_limit + $coeff) && $i <= $paged + ($paginate_limit + $coeff) )) {
                    // reset the show dots flag 
                    $dotshow = true;
                    // If it's the current page, leave out the link // otherwise set a URL field also 

                    if ($i != $paged) {
                        echo "<a href='#' onclick='waj_get_page(" . $i . ",\"" . $product_cat . "\"); return false;' class=\"woo_paged\">" . $i . "</a>";
                    } else {
                        echo "<span class=\"current\">" . $i . "</span>";
                    }

                    // If ellipses dots are to be displayed 
                    // (page navigation skipped) 
                } else if ($dotshow == true) {
                    // set it to false, so that more than one // set of ellipses is not displayed 
                    $dotshow = false;
                    echo "<span>...</span>";
                }
            }

            echo '</td><td>';
                if ($paged < $pages) {
                    echo "<a href='#'  class='next iwaj_af' onclick='waj_get_page(" . ($paged + 1) . ",\"" . $product_cat . "\"); return false;'>" . WAJ_Settings::get_setting('next_text', 'Next') . " </a>";
                } else {
                    echo "<span class='disabled next iwaj_af'> " . WAJ_Settings::get_setting('next_text', 'Next') . "</span>";
                }
            echo '</td>';

            echo "</tr></table></div>\n";
        endif;
    }

    /**
	 * WooCommerce Extra Feature
	 * --------------------------
	 *
	 * Register a shortcode that creates a product categories dropdown list
	 *
	 * Use: [product_categories_dropdown orderby="title" count="0" hierarchical="0"]
	 *
	 */

	public function output_dropdowns( $atts , $selected ) {

            ob_start();

            if ( function_exists('woocommerce_catalog_ordering') && WAJ_Settings::get_setting('order_dropbdown') ) :
                echo woocommerce_catalog_ordering();
            endif;               

            if ( function_exists( 'wc_product_dropdown_categories' ) && WAJ_Settings::get_setting('category_dropbdown') ) :
                // Stuck with this until a fix for http://core.trac.wordpress.org/ticket/13258
                wc_product_dropdown_categories( $atts );                
                ?>
                <script type='text/javascript'>
                /* <![CDATA[ */
                var product_cat_dropdown = document.querySelector(".dropdown_product_cat");

                function onProductCatChange() {
                        //if ( product_cat_dropdown.options[product_cat_dropdown.selectedIndex].value !=='' ) {
                                waj_get_page(1, product_cat_dropdown.options[product_cat_dropdown.selectedIndex].value);
                        //}
                }
                //product_cat_dropdown.options[product_cat_dropdown.selectedIndex].text = '...select category';

                product_cat_dropdown.onchange = onProductCatChange;
                <?php
                        if ($selected != '') {
                                echo "product_cat_dropdown.value = '{$selected}';";
                        }
                ?>
                /* ]]> */
                </script>
                <?php
            endif;

            return ob_get_clean();

	}	
	
	function AJAX_get () {
            $paged = intval( $_GET['paged'] );				
            $product_cat = sanitize_text_field( $_GET['product_cat'] );		

            WAJ_Functions::die_json_encode( 
                array(
                    "html"=> $this->products_list( array( "per_page"=>3, "columns"=>3, "paged"=>$paged, "product_cat"=>$product_cat ) )
                )
            );
	}	
	
}
