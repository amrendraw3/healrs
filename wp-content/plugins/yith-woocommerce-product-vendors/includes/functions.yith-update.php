<?php

/**
 * Database Table Check
 */

//Check if Commission tables are created
function yith_vendors_check_commissions_table() {
    $create_commissions_table = get_option( 'yith_product_vendors_commissions_table_created' );
    if ( ! $create_commissions_table ) {
        /**
         * Create new Commissions DB table
         */
        YITH_Commissions::create_commissions_table();
    }
}

add_action( 'admin_init', 'yith_vendors_check_commissions_table' );

/**
 * Database Version Update
 */

//Add support to YITH Product Vendors db version 1.0.1
function yith_vendors_update_db_1_0_1() {
    $vendors_db_option = get_option( 'yith_product_vendors_db_version', '1.0.0' );
    if ( $vendors_db_option && version_compare( $vendors_db_option, '1.0.1', '<' ) ) {
        global $wpdb;
        $termmeta_table     = YITH_Vendors()->termmeta_table;
        $termmeta_term_id   = YITH_Vendors()->termmeta_term_id;
        $sql = "SELECT {$termmeta_term_id} as vendor_id, meta_value as user_id
                    FROM {$termmeta_table} as wtm
                    WHERE wtm.meta_key = %s
                    AND {$termmeta_table} IN (
                        SELECT DISTINCT term_id as vendor_id
                        FROM {$wpdb->term_taxonomy} as tt
                        WHERE tt.taxonomy = %s
                    )";

        $results = $wpdb->get_results( $wpdb->prepare( $sql, 'owner', YITH_Vendors()->get_taxonomy_name() ) );

        foreach ( $results as $result ) {
            $user = get_user_by( 'id', $result->user_id );

            if ( $user ) {
                YITH_Vendors()->update_term_meta( $result->vendor_id, 'registration_date', get_date_from_gmt( $user->user_registered ) );
                YITH_Vendors()->update_term_meta( $result->vendor_id, 'registration_date_gmt', $user->user_registered );
                if( defined( 'YITH_WPV_PREMIUM' ) ){
                    $user->add_cap( 'view_woocommerce_reports' );
                }
            }
        }

        update_option( 'yith_product_vendors_db_version', '1.0.1' );
    }
}

//Add support to YITH Product Vendors db version 1.0.2
function yith_vendors_update_db_1_0_2() {
    $vendors_db_option = get_option( 'yith_product_vendors_db_version', '1.0.0' );
    if ( $vendors_db_option && version_compare( $vendors_db_option, '1.0.2', '<' ) ) {
        global $wpdb;

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_vendors_commissions` CHANGE `rate` `rate` DECIMAL(5,4) NOT NULL";
        $wpdb->query( $sql );

        update_option( 'yith_product_vendors_db_version', '1.0.2' );
    }
}

//Add support to YITH Product Vendors db version 1.0.3
function yith_vendors_update_db_1_0_3(){
    $vendors_db_option = get_option( 'yith_product_vendors_db_version', '1.0.0' );
    if ( $vendors_db_option && version_compare( $vendors_db_option, '1.0.3', '<' ) ) {
        /**
         * Create "Become a Vendor" and Terms and Conditions Pages
         */
         if( defined( 'YITH_WPV_PREMIUM' ) ){
             YITH_Vendors_Admin_Premium::create_plugins_page();
         }

        /**
         * Show Gravatar Option
         */
        $vendors = YITH_Vendors()->get_vendors();
        foreach( $vendors as $vendor ){
            if( empty( $vendor->show_gravatar ) )  {
                $vendor->show_gravatar = 'yes';
            }
        }
        update_option( 'yith_product_vendors_db_version', '1.0.3' );
    }
}

//Add support to YITH Product Vendors plugin version 1.8.1
function yith_vendors_update_db_1_0_4() {
    $vendors_db_option = get_option( 'yith_product_vendors_db_version', '1.0.0' );
    if ( $vendors_db_option && version_compare( $vendors_db_option, '1.0.4', '<' ) ) {
        /**
         * Create "Become a vendor" and "Terms and conditions" Pages
         */
        if( defined( 'YITH_WPV_PREMIUM' ) ){
            YITH_Vendors_Admin_Premium::create_plugins_page();
        }
        update_option( 'yith_product_vendors_db_version', '1.0.4' );
    }
}

//Add support to YITH Product Vendors plugin version 1.9.6
function yith_vendors_update_db_1_0_5() {
    $vendors_db_option = get_option( 'yith_product_vendors_db_version', '1.0.0' );
    if ( $vendors_db_option && version_compare( $vendors_db_option, '1.0.5', '<' ) ) {
        /**
         * Create new DB table yith_vendors_payments and yith_vendors_payments_relathionship
         */
        if( defined( 'YITH_WPV_PREMIUM' ) ){
            YITH_Commissions::create_transaction_table();
        }
        update_option( 'yith_product_vendors_db_version', '1.0.5' );
    }
}

//Add support to YITH Product Vendors plugin version 1.9.6
function yith_vendors_update_db_1_0_6() {
    $vendors_db_option = get_option( 'yith_product_vendors_db_version', '1.0.0' );
    if ( $vendors_db_option && version_compare( $vendors_db_option, '1.0.6', '<' ) ) {
        $vendor_role_name = YITH_Vendors()->get_role_name();
        $vendor_role = get_role( $vendor_role_name );
        if( $vendor_role instanceof WP_Role ){
            $vendor_role->add_cap( 'edit_posts' );    
        }
        update_option( 'yith_product_vendors_db_version', '1.0.6' );
    }

}

//Add support to YITH Product Vendors plugin version 1.11.4
function yith_vendors_update_db_1_0_7() {
    $vendors_db_option = get_option( 'yith_product_vendors_db_version', '1.0.0' );
    if ( $vendors_db_option && version_compare( $vendors_db_option, '1.0.7', '<' ) ) {
        global $wpdb;
        if( ! empty( $wpdb ) ){
            $query = $wpdb->prepare( "DELETE FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key IN( %s, %s )", '_parent__commission_included_coupon', '_parent__commission_included_tax' );
            $wpdb->query( $query );
        }
        update_option( 'yith_product_vendors_db_version', '1.0.7' );
    }

}

//Add support to YITH Product Vendors plugin version 1.12.0
function yith_vendors_update_db_1_0_8(){
    $vendors_db_option = get_option( 'yith_product_vendors_db_version', '1.0.0' );
    if ( $vendors_db_option && version_compare( $vendors_db_option, '1.0.8', '<' ) ) {
        $skin_option_id = 'yith_vendors_skin_header';
        $background_option_id = 'yith_skin_background_color';
        $font_color_option_id = 'yith_skin_font_color';

        $old_skin = get_option( $skin_option_id, 'skin1');

        if ('skin1' == $old_skin) {
            update_option($background_option_id, '#000000');
            update_option($font_color_option_id, '#ffffff');
        } else {
            update_option($background_option_id, '#ffffff');
            update_option($font_color_option_id, '#000000');
        }

        update_option($skin_option_id, 'small-box');
        update_option( 'yith_product_vendors_db_version', '1.0.8' );
    }
}

//Add support to YITH Product Vendors plugin version 1.12.1
function yith_vendors_update_db_1_0_9() {
    $vendors_db_option = get_option( 'yith_product_vendors_db_version', '1.0.0' );
    if ( $vendors_db_option && version_compare( $vendors_db_option, '1.0.9', '<' ) ) {
        $vendor_role_name = YITH_Vendors()->get_role_name();
        $vendor_role = get_role( $vendor_role_name );
        if( $vendor_role instanceof WP_Role ){
            //Fix: vendor admins can't edit orders
            $vendor_role->add_cap( 'edit_others_shop_orders' );
        }
        update_option( 'yith_product_vendors_db_version', '1.0.9' );
    }
}

//ALTER TABLE `wp_yith_vendors_payments` ;
//Add support to shipping module
function yith_vendors_update_db_1_1_0() {
    $vendors_db_option = get_option( 'yith_product_vendors_db_version', '1.0.0' );
    if ( $vendors_db_option && version_compare( $vendors_db_option, '1.1.0', '<' ) ) {
        global $wpdb;

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_vendors_commissions` ADD `type` VARCHAR(30) NOT NULL DEFAULT 'product' AFTER `status`";
        $wpdb->query( $sql );

        update_option( 'yith_product_vendors_db_version', '1.1.0' );
    }
}


add_action( 'admin_init', 'yith_vendors_update_db_1_0_1' );
add_action( 'admin_init', 'yith_vendors_update_db_1_0_2' );
add_action( 'admin_init', 'yith_vendors_update_db_1_0_3' );
add_action( 'admin_init', 'yith_vendors_update_db_1_0_4' );
add_action( 'admin_init', 'yith_vendors_update_db_1_0_5' );
add_action( 'admin_init', 'yith_vendors_update_db_1_0_6' );
add_action( 'admin_init', 'yith_vendors_update_db_1_0_7' );
add_action( 'admin_init', 'yith_vendors_update_db_1_0_8' );
add_action( 'admin_init', 'yith_vendors_update_db_1_0_9' );
add_action( 'admin_init', 'yith_vendors_update_db_1_1_0' );

/**
 * Plugin Version Update
 */

//Add support to YITH Product Vendors plugin version 1.8.1
function yith_vendors_plugin_update_1_8_1() {
    $plugin_version = get_option( 'yith_wcmv_version', '1.0.0' );
    if ( version_compare( $plugin_version, YITH_Vendors()->version, '<' ) ) {
        // _money_spent and _order_count may be out of sync - clear them
        delete_metadata( 'user', 0, '_money_spent', '', true );
        delete_metadata( 'user', 0, '_order_count', '', true );
    }
}

//priority set to 20 after vendor register taxonomy
add_action( 'admin_init', 'yith_vendors_plugin_update_1_8_1', 20 );

/**
 * Regenerate Vendor Role Capabilities fter update by FTP
 */

function yith_vendors_plugin_update() {
    $plugin_version = get_option( 'yith_wcmv_version', '1.0.0' );
    if ( version_compare( $plugin_version, YITH_Vendors()->version, '<' ) ) {
        /* Check if Vendor Role Exists */
        YITH_Vendors::add_vendor_role();
        /* Add Vendor Role to vendor owner and admins */
        YITH_Vendors::setup( 'add_role' );
        update_option( 'yith_wcmv_version', YITH_Vendors()->version );
    }
}
//priority set to 30 after vendor register taxonomy and other actions
add_action( 'admin_init', 'yith_vendors_plugin_update', 30 );

// Deprecated hook yith_wcmv_show_vendor_profile. Look: includes/class.yith-vendors-admin-premium.php:185
add_filter( 'yith_wcmv_hide_vendor_profile', 'yith_deprecated_show_vendor_profile_hook', 9999 );
function yith_deprecated_show_vendor_profile_hook( $value ){
    return apply_filters( 'yith_wcmv_show_vendor_profile', $value );
}