<?php
/**
 * WCFM plugin core
 *
 * Plugin non Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfm/core
 * @version   1.1.6
 */
 
class WCFM_Non_Ajax {

	public function __construct() {
		global $WCFM;
		
		// WCFM Dashboard Product Stats Report - 3.0.0
		add_action( 'after_wcfm_dashboard_left_col', array( &$this, 'wcfm_dashboard_product_stats_report' ) );
		
		// WCFM Dashboard Sales Report
		add_action( 'after_wcfm_dashboard_sales_report', array( &$this, 'wcfm_dashboard_sales_report' ) );
		
		// Plugins page help links
		add_filter( 'plugin_action_links_' . $WCFM->plugin_base_name, array( &$this, 'wcfm_plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( &$this, 'wcfm_plugin_row_meta' ), 10, 2 );
		
		add_action( 'admin_bar_menu', array( &$this, 'wcfm_admin_bar_menu' ), 100 );
		
	}
	
	/**
	 * WCFM Dashboard Product Stats Report
	 */
	function wcfm_dashboard_product_stats_report() {
		global $WCFM, $wpdb;
		
		$wcfm_is_allow_analytics = apply_filters( 'wcfm_is_allow_analytics', true );
		$is_wcfm_analytics_enable = is_wcfm_analytics();
		if( !$is_wcfm_analytics_enable || !$wcfm_is_allow_analytics ) return;
		
		$args = array(
							'posts_per_page'   => -1,
							'offset'           => 0,
							'category'         => '',
							'category_name'    => '',
							'orderby'          => 'date',
							'order'            => 'DESC',
							'include'          => '',
							'exclude'          => '',
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => 'product',
							'post_mime_type'   => '',
							'post_parent'      => '',
							//'author'	   => get_current_user_id(),
							'post_status'      => array('draft', 'pending', 'publish'),
							'suppress_filters' => true 
						);
		
		$args = apply_filters( 'wcfm_products_args', $args );
		
		$wcfm_products_array = get_posts( $args );
		
		$total_count = count($wcfm_products_array);
		$draft_count = 0;
		$pending_count = 0;
		$publish_count = 0;
		if(!empty($wcfm_products_array)) {
			foreach($wcfm_products_array as $wcfm_products_single) {
				switch( $wcfm_products_single->post_status ) {
				  case 'publish' :
				  	$publish_count++;
				  break;
				  
				  case 'pending' :
				  	$pending_count++;
				  break;
				  
				  case 'draft' :
				  	$draft_count++;
				  break;
				}
			}
		}
		$product_stat = "[[0, " . $publish_count . "],[1, " . $pending_count . "],[2, " . $draft_count . "]]";
		?>
		<script type="text/javascript">
			var product_stat_data = <?php echo $product_stat; ?>;
			jQuery(document).ready(function ($) {
				var product_stat_dataset = [{ data: product_stat_data, color: "#5482FF" }];
				var ticks = [[0, "<?php _e( 'Online', 'wc-frontend-manager' ); ?>"], [1, "<?php _e( 'Pending', 'wc-frontend-manager' ); ?>"], [2, "<?php _e( 'Draft', 'wc-frontend-manager' ); ?>"]];
	
				var options = {
						series: {
								bars: {
										show: true
								}
						},
						bars: {
								align: "center",
								barWidth: 0.5
						},
						xaxis: {
								axisLabel: "<?php _e( 'Product Types', 'wc-frontend-manager' ); ?>",
								axisLabelUseCanvas: true,
								axisLabelFontSizePixels: 12,
								axisLabelFontFamily: 'Verdana, Arial',
								axisLabelPadding: 10,
								ticks: ticks
						},
						yaxis: {
								axisLabel: "<?php _e( 'Product Count', 'wc-frontend-manager' ); ?>",
								axisLabelUseCanvas: true,
								axisLabelFontSizePixels: 12,
								axisLabelFontFamily: 'Verdana, Arial',
								axisLabelPadding: 3,
								tickFormatter: function (v, axis) {
										return v;
								}
						},
						legend: {
								noColumns: 0,
								labelBoxBorderColor: "#000000",
								position: "nw"
						},
						grid: {
								hoverable: true,
								borderWidth: 2,
								backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
						}
				};
				
				function gd(year, month, day) {
            return new Date(year, month, day).getTime();
        }
 
        var previousPoint = null, previousLabel = null;
 
        $.fn.UseTooltip = function () {
            $(this).bind("plothover", function (event, pos, item) {
                if (item) {
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();
 
                        var x = item.datapoint[0];
                        var y = item.datapoint[1];
 
                        var color = item.series.color;
 
                        //console.log(item.series.xaxis.ticks[x].label);                
 
                        showTooltip(item.pageX,
                        item.pageY,
                        color,
                        item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong>");
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        };
 
        function showTooltip(x, y, color, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }
        
        
        jQuery('#product_stats-report').css( 'width', jQuery('#wcfm_dashboard_wcfm_product_stats_expander').width() + 'px' );
				jQuery.plot(jQuery("#product_stats-report"), product_stat_dataset, options);
				jQuery("#product_stats-report").UseTooltip();
			});
  	</script>
		<?php
		
	}
	
	/**
	 * WCFM Dashboard Sales Report
	 */
	function wcfm_dashboard_sales_report() {
		global $WCFM, $wpdb;
		
		$wcfm_is_allow_reports = apply_filters( 'wcfm_is_allow_reports', true );
		if( !$wcfm_is_allow_reports ) return;
			
		$query            = array();
		$query['fields']  = "SELECT SUM( order_item_meta.meta_value ) as qty, order_item_meta_2.meta_value as product_id
			FROM {$wpdb->posts} as posts";
		$query['join']    = "INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON posts.ID = order_id ";
		$query['join']   .= "INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id ";
		$query['join']   .= "INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id ";
		$query['where']   = "WHERE posts.post_type IN ( 'shop_order','shop_order_refund' ) ";
		$query['where']  .= "AND posts.post_status IN ( 'wc-" . implode( "','wc-", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "' ) ";
		$query['where']  .= "AND order_item_meta.meta_key = '_qty' ";
		$query['where']  .= "AND order_item_meta_2.meta_key = '_product_id' ";
		$query['where']  .= "AND posts.post_date >= '" . date( 'Y-m-d', strtotime( '-7 DAY', current_time( 'timestamp' ) ) ) . "' ";
		$query['where']  .= "AND posts.post_date <= '" . date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) . "' ";
		$query['groupby'] = "GROUP BY product_id";
		$query['orderby'] = "ORDER BY qty DESC";
		$query['limits']  = "LIMIT 5";
		
		$top_sellers = $wpdb->get_results( implode( ' ', apply_filters( 'woocommerce_dashboard_status_widget_top_seller_query', $query ) ) );
		$top_sellers_array = '';
		//$colors_arr = array( '#00A36A', '#005CDE', '#7D0096', '#992B00', '#DE000F', '#ED7B00' );
		$colors_arr = apply_filters( 'wcfm_sales_by_product_pie_chart_colors', array( '#00897b', '#D15600', '#356AA0', '#C79810', '#B02B2C', '#D01F3C' ) );
		$top_seller_pro = '';
		if( !empty($top_sellers) ) {
			foreach( $top_sellers as $index => $top_seller ) {
				if(!$top_seller_pro) $top_seller_pro = $top_seller->product_id;
				if(!$top_sellers_array) $top_sellers_array = '[';
				else $top_sellers_array .= ',';
				$top_sellers_array .= '{label:"' . get_the_title( $top_seller->product_id ) . '", data:' . $top_seller->qty . ', color: "' . $colors_arr[$index] . '"}';
			}
		} else {
			$top_sellers_array .= '[{label:"' . __( 'No sales yet ..!!!', 'wc-frontend-manager' ) . '", data: 1, color: "' . $colors_arr[0] . '"}';
		}
		$top_sellers_array .= ']';
		?>
		<script type="text/javascript">
		  var top_sellers_array = <?php echo ($top_sellers_array); ?>;
		  jQuery(document).ready(function($) {
			var options = {
				              grid: {
												hoverable: true
											},
											series: {
													pie: {
														show: true,
														innerRadius: 0.3,
														tilt: 0.7,
														label: {      
														show:true
													}
												},
												enable_tooltip: true,
											},
											legend: { 
											  show: false,
											}
										};
										
			 $.plot($("#sales-piechart"), top_sellers_array, options);
		} );
    </script>
		<?php
	}
	
	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public function wcfm_plugin_action_links( $links ) {
		global $WCFM;
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wcfm_settings' ) . '" aria-label="' . esc_attr__( 'View WCFM settings', 'wc-frontend-manager' ) . '">' . esc_html__( 'Settings', 'wc-frontend-manager' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param	mixed $links Plugin Row Meta
	 * @param	mixed $file  Plugin Base file
	 * @return	array
	 */
	public function wcfm_plugin_row_meta( $links, $file ) {
		global $WCFM;
		if ( $WCFM->plugin_base_name == $file ) {
			$row_meta = array(
				'docs'      => '<a href="' . esc_url( apply_filters( 'wcfm_docs_url', 'http://wclovers.com/knowledgebase/' ) ) . '" aria-label="' . esc_attr__( 'View WCFM documentation', 'wc-frontend-manager' ) . '">' . esc_html__( 'Documentation', 'wc-frontend-manager' ) . '</a>',
				'faq'       => '<a href="' . esc_url( apply_filters( 'wcfm_faq_url', 'http://wclovers.com/faq/' ) ) . '" aria-label="' . esc_attr__( 'View WCFM FAQ', 'wc-frontend-manager' ) . '">' . esc_html__( 'FAQ', 'wc-frontend-manager' ) . '</a>',
				'support'   => '<a href="' . esc_url( apply_filters( 'wcfm_support_url', 'http://wclovers.com/forums' ) ) . '" aria-label="' . esc_attr__( 'Visit premium customer support', 'woocommerce' ) . '">' . esc_html__( 'Support', 'woocommerce' ) . '</a>',
				//'contactus' => '<a href="' . esc_url( apply_filters( 'wcfm_contactus_url', 'http://wclovers.com/contact-us/' ) ) . '" aria-label="' . esc_attr__( 'Any WC help feel free to contact us', 'wc-frontend-manager' ) . '">' . esc_html__( 'Contact US', 'wc-frontend-manager' ) . '</a>'
			);
			
			$ultimate_meta = array();
			if(!WCFM_Dependencies::wcfmu_plugin_active_check()) {
				$ultimate_meta = array( 'ultimate' => '<a href="' . esc_url( apply_filters( 'wcfm_ultimate_url', 'http://wclovers.com/product/woocommerce-frontend-manager-ultimate/' ) ) . '" aria-label="' . esc_attr__( 'Add more power to your WCFM', 'wc-frontend-manager' ) . '">' . esc_html__( 'WCFM Ultimate', 'wc-frontend-manager' ) . '</a>' );
			}

			return array_merge( $links, $row_meta, $ultimate_meta );
		}

		return (array) $links;
	}
	
	function wcfm_admin_bar_menu() {
		global $WCFM, $wp_admin_bar;
		
		$wcfm_menus = $WCFM->get_wcfm_menus();
		//unset($wcfm_menus['settings']);
		
		$title = '<div class="wcfm-admin-menu-head"><img src="' . $WCFM->plugin_url . '/assets/images/wcfm-30x30.png" alt="WCFM Home" /><span class="screen-reader-text">' . __( 'WCFM', 'wordpress-seo' ) . '</span></div>';
		
		$wp_admin_bar->add_menu( array(
			'id'    => 'wcfm-menu',
			'title' => $title,
			'href'  => get_wcfm_url(),
			'meta'   => array( 'tabindex' => 0 )
		) );
		
		if( !empty($wcfm_menus) ) {
			foreach( $wcfm_menus as $wcfm_menu_key => $wcfm_menu_data ) {
				if( !isset( $wcfm_menu_data['capability'] ) || empty( $wcfm_menu_data['capability'] ) || current_user_can( $wcfm_menu_data['capability'] ) ) {
					$wp_admin_bar->add_menu( array(
						'parent'    => 'wcfm-menu',
						'id' => 'wcfm-menu-'. $wcfm_menu_key,
						'title' => '<span class="wcfm-admin-menu">' . $wcfm_menu_data['label'] . '</span>',
						'href'  => $wcfm_menu_data['url'],
						'meta'   => array( 'tabindex' => 0 )
					) );
					
					if( isset( $wcfm_menu_data['has_new'] ) ) {
						$wp_admin_bar->add_menu( array(
							'parent'    => 'wcfm-menu-'. $wcfm_menu_key,
							'id' => 'wcfm-menu-sub-parent-'. $wcfm_menu_key,
							'title' => '<span class="wcfm-admin-menu">' . $wcfm_menu_data['label'] . '</span>',
							'href'  => $wcfm_menu_data['url'],
							'meta'   => array( 'tabindex' => 0 )
						) );
						$wp_admin_bar->add_menu( array(
							'parent'    => 'wcfm-menu-'. $wcfm_menu_key,
							'id' => 'wcfm-menu-sub-'. $wcfm_menu_key,
							'title' => '<span class="wcfm-admin-menu">' . __( 'Add New', 'wc-frontend-manager' ) . '</span>',
							'href'  => $wcfm_menu_data['new_url'],
							'meta'   => array( 'tabindex' => 0 )
						) );
					}
				}
			}
		}
		
		/*if( is_admin() ) {
			$wp_admin_bar->add_menu( array(
				'parent'    => 'wcfm-menu',
				'id' => 'wcfm-menu-settings',
				'title' => '<span class="wcfm-admin-menu"><span class="fa fa-cog"></span>' . __( 'Settings', 'wc-frontend-manager' ) . '</span>',
				'href'  => admin_url( 'admin.php?page=wcfm_settings' ),
				'meta'   => array( 'tabindex' => 0 )
			) );
		} else {
			$wp_admin_bar->add_menu( array(
				'parent'    => 'wcfm-menu',
				'id' => 'wcfm-menu-settings',
				'title' => '<span class="wcfm-admin-menu"><span class="fa fa-cog"></span>' . __( 'Settings', 'wc-frontend-manager' ) . '</span>',
				'href'  => get_wcfm_settings_url(),
				'meta'   => array( 'tabindex' => 0 )
			) );
		}*/
	}
}