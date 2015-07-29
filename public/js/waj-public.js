function waj_get_page(page, category, order, scroll) {

    if (!page) {
        page = 1;
    }
    if (category == undefined)
    {
        category = '';
    }
    if (scroll == undefined)
    {
        scroll = true;
    }
    if (order == undefined)
    {
        order = '';
    }
    //console.log(category);
    jQuery(".headway_woo ul.products").addClass("preloader");
    //return;
    jQuery.get(WAJ.ajax_url, {action: 'woo_ajax_nav', paged: page, product_cat: category, orderby: order, rand: waj_randomString(10)}, 'html')
            .done(function (data) {
                data = waj_parse_json(data);
                //$jQueryparent = jQuery('div.woocommerce.headway_woo').parent('div');
                //jQuery('div.woocommerce.headway_woo').remove();
                jQuery(".woo_ajax_nav").replaceWith(data.html);
                jQuery(".woo_ajax_nav .products .type-product").addClass("product");


                if (jQuery('.headway_woo div#waj_pagination').length && scroll) {
                    waj_scrollToBottom(jQuery('.headway_woo div#waj_pagination'));
                    //setTimeout("", 300);
                }
                //MagicThumb.refresh();									
                waj_init();
            });

    return false;
}

jQuery(document).ready(function () {
    waj_init();
});


// Add hanlers to Order dropbown
function waj_init() {
    // Check if order from exists
    if ( document.querySelector('form.woocommerce-ordering') != null ) {
        jQuery("form.woocommerce-ordering select.orderby").on("change", function () {
            waj_get_page(1, jQuery('#woo_categories .dropdown_product_cat').val(), jQuery('.woo_ajax_nav form.woocommerce-ordering select.orderby').val(), false);
            return false;
        });
        jQuery("form.woocommerce-ordering").on("submit", function () {
            waj_get_page(1, jQuery('#woo_categories .dropdown_product_cat').val(), jQuery('.woo_ajax_nav form.woocommerce-ordering select.orderby').val(), false);
            return false;
        });
    }
}

function waj_parse_json(data) {
    try {
        // Get the valid JSON only from the returned string
        if (data.indexOf('<!--WAJ_START-->') >= 0)
            data = data.split('<!--WAJ_START-->')[1]; // Strip off before after WC_START

        if (data.indexOf('<!--WAJ_END-->') >= 0)
            data = data.split('<!--WAJ_END-->')[0]; // Strip off anything after WC_END
        // Parse
        result = jQuery.parseJSON(data);

        //console.log( result.result === 'success' );
        if (result) {
            return result;
        } else {
            throw 'Invalid response';
        }
    }
    catch (err) {
        alert('Error: ' + err);
    }
}


function waj_randomString(length) {
    var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');
    if (!length)
        length = Math.floor(Math.random() * chars.length);
    var str = '';
    for (var i = 0; i < length; i++) {
        str += chars[Math.floor(Math.random() * chars.length)];
    }
    return str;
}

function waj_scrollToBottom(el) {
    div_height = jQuery(el).height();
    div_offset = jQuery(el).offset().top;
    window_height = jQuery(window).height();
    jQuery('html,body').scrollTop(div_offset - window_height + div_height + 60);
}
