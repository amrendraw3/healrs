<?php
/**
 * WCFM plugin controllers
 *
 * Plugin WC Marketplace Setings Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/controllers
 * @version   2.2.5
 */

class WCFM_Settings_WCMarketplace_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST;
		
		$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		
		$wcfm_settings_form_data = array();
	  parse_str($_POST['wcfm_settings_form'], $wcfm_settings_form);
	  
	  // sanitize
		//$wcfm_settings_form = array_map( 'sanitize_text_field', $wcfm_settings_form );
		//$wcfm_settings_form = array_map( 'stripslashes', $wcfm_settings_form );
		
		// sanitize html editor content
		$wcfm_settings_form['shop_description'] = ! empty( $_POST['profile'] ) ? stripslashes( html_entity_decode( $_POST['profile'], ENT_QUOTES, 'UTF-8' ) ) : '';
		update_user_meta( $user_id, '_vendor_description', $wcfm_settings_form['shop_description'] );
		
		$wcfm_setting_store_fields = array( 
																					'_vendor_page_title'          => 'shop_name',
																					'_vendor_page_slug'           => 'shop_slug',
																					'_vendor_image'               => 'wcfm_logo',
																					'_vendor_policy_tab_title'    => 'vendor_policy_tab_title',
																					'_vendor_shipping_policy'     => 'vendor_shipping_policy',
																					'_vendor_refund_policy'       => 'vendor_refund_policy',
																					'_vendor_cancellation_policy' => 'vendor_cancellation_policy',
																					'_vendor_customer_phone'      => 'vendor_customer_phone',
																					'_vendor_customer_email'      => 'vendor_customer_email',
																					'_vendor_csd_return_address1' => 'vendor_csd_return_address1',
																					'_vendor_csd_return_address2' => 'vendor_csd_return_address2',
																					'_vendor_csd_return_country'  => 'vendor_csd_return_country',
																					'_vendor_csd_return_state'    => 'vendor_csd_return_state',
																					'_vendor_csd_return_city'     => 'vendor_csd_return_city',
																					'_vendor_csd_return_zip'      => 'vendor_csd_return_zip'
																			  );
		foreach( $wcfm_setting_store_fields as $wcfm_setting_store_key => $wcfm_setting_store_field ) {
			if( isset( $wcfm_settings_form[$wcfm_setting_store_field] ) ) {
				update_user_meta( $user_id, $wcfm_setting_store_key, $wcfm_settings_form[$wcfm_setting_store_field] );
			}
		}
		
		// Update Page Title
		$vendor = new WCMp_Vendor( $user_id );
		if( $vendor ) {
			$vendor->update_page_title( wc_clean( $wcfm_settings_form['shop_name'] ) );
			if( isset( $wcfm_settings_form['shop_slug'] ) && !empty( $wcfm_settings_form['shop_slug'] ) ) {
				$vendor->update_page_slug( wc_clean( $wcfm_settings_form['shop_slug'] ) );
			}
		}
		
				// Store Adcanced settings
		$wcfm_settings_store_fields = array( 	'_vendor_phone'      => 'shop_phone',
																					//'_vendor_email'      => 'shop_email',
																					'_vendor_banner'     => 'banner',
																					'_vendor_address_1'  => 'addr_1',
																					'_vendor_address_2'  => 'addr_2',
																					'_vendor_country'    => 'country',
																					'_vendor_city'       => 'city',
																					'_vendor_state'      => 'state',
																					'_vendor_postcode'   => 'zip'
																			  );
		
		foreach( $wcfm_settings_store_fields as $wcfm_settings_store_key => $wcfm_settings_store_field ) {
			update_user_meta( $user_id, $wcfm_settings_store_key, $wcfm_settings_form[$wcfm_settings_store_field] );
		}
  	
		// Billing Settings
  	$wcfm_setting_bank_fields = array( 	'_vendor_paypal_email'          => 'paypal_email',
																				'_vendor_bank_account_type'     => '_vendor_bank_account_type',
																				'_vendor_bank_account_number'   => '_vendor_bank_account_number',
																				'_vendor_bank_name'             => '_vendor_bank_name',
																				'_vendor_aba_routing_number'    => '_vendor_aba_routing_number',
																				'_vendor_bank_address'          => '_vendor_bank_address',
																				'_vendor_destination_currency'  => '_vendor_destination_currency',
																				'_vendor_iban'                  => '_vendor_iban',
																				'_vendor_account_holder_name'   => '_vendor_account_holder_name',
																				'_vendor_payment_mode'          => '_vendor_payment_mode'
																			);
		foreach( $wcfm_setting_bank_fields as $wcfm_setting_bank_key => $wcfm_setting_bank_field ) {
			update_user_meta( $user_id, $wcfm_setting_bank_key, $wcfm_settings_form[$wcfm_setting_bank_field] );
		}
		
		do_action( 'wcfm_wcmarketplace_settings_update', $user_id, $wcfm_settings_form );
		
		echo '{"status": true, "message": "' . __( 'Settings saved successfully', 'wc-frontend-manager' ) . '"}';
		 
		die;
	}
}