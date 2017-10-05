<?php
/**
 * Report class responsible for handling analytics reports.
 *
 * @since      2.6.3
 *
 * @package    WooCommerce Frontend Manager
 * @subpackage wcfm/includes/reports
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );

class WCFM_Report_Analytics extends WC_Admin_Report {
	public $chart_colors = array();
	public $current_range;
	private $report_data;

	/**
	 * Constructor
	 *
	 * @access public
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return bool
	 */
	public function __construct() {
		global $WCFM;
		$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( $_GET['range'] ) : '7day';

		if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', 'month', '7day' ) ) ) {
			$current_range = '7day';
		}

		$this->current_range = $current_range;
	}

	/**
	 * Get the report data
	 *
	 * @access public
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return array of objects
	 */
	public function get_report_data() {
		global $WCFM;
		if ( empty( $this->report_data ) ) {
			$this->query_report_data();
		}

		return $this->report_data;
	}

	/**
	 * Get the report based on parameters
	 *
	 * @access public
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return array of objects
	 */
	public function query_report_data() {
		global $wpdb, $WCFM;

		$this->report_data = new stdClass;

		$sql = "SELECT * FROM {$wpdb->prefix}wcfm_daily_analysis AS analytics";

		$sql .= " WHERE 1=1";
		
		if( wcfm_is_vendor() ) {
			$sql .= " AND analytics.author_id = %d";
			$sql .= " AND analytics.is_store = 1";
		} else {
			$sql .= " AND analytics.is_shop = 1";
		}
			

		switch( $this->current_range ) {
			case 'year' :
				$sql .= " AND YEAR( analytics.visited ) = YEAR( CURDATE() )";
				break;

			case 'last_month' :
				$sql .= " AND MONTH( analytics.visited ) = MONTH( NOW() ) - 1";
				break;

			case 'month' :
				$sql .= " AND MONTH( analytics.visited ) = MONTH( NOW() )";
				break;

			case 'custom' :
				$start_date = ! empty( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : '';
				$end_date = ! empty( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : '';

				$sql .= " AND DATE( analytics.visited ) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
				break;

			case 'default' :
			case '7day' :
				$sql .= " AND DATE( analytics.visited ) BETWEEN DATE_SUB( NOW(), INTERVAL 7 DAY ) AND NOW()";
				break;
		}

		// Enable big selects for reports
		$wpdb->query( 'SET SESSION SQL_BIG_SELECTS=1' );

		if( wcfm_is_vendor() ) {
			$is_marketplece = wcfm_is_marketplace();
			if( $is_marketplece == 'wcpvendors' ) {
				$results = $wpdb->get_results( $wpdb->prepare( $sql, apply_filters( 'wcfm_current_vendor_id', WC_Product_Vendors_Utils::get_logged_in_vendor() ) ) );
			} else {
				$results = $wpdb->get_results( $wpdb->prepare( $sql, apply_filters( 'wcfm_current_vendor_id', get_current_user_id() ) ) );
			}
		} else {
			$results = $wpdb->get_results( $sql );
		}

		$view_count                    = 0;

		foreach( $results as $data ) {
			$view_count = $data->count;
		}

		$this->report_data->view_count          = $view_count;
	}

	/**
	 * Get the legend for the main chart sidebar
	 * @return array
	 */
	public function get_chart_legend() {
		global $WCFM;
		$legend = array();
		$data   = $this->get_report_data();

		switch ( $this->chart_groupby ) {
			case 'day' :
				$average_sales_title = sprintf( __( '%s average daily sales', 'wc-frontend-manager' ), '<strong>' . wc_price( $data->average_sales ) . '</strong>' );
			break;
			case 'month' :
			default :
				$average_sales_title = sprintf( __( '%s average monthly sales', 'wc-frontend-manager' ), '<strong>' . wc_price( $data->average_sales ) . '</strong>' );
			break;
		}
		
		$legend[] = array(
			'title'            => sprintf( __( '%s total earned commission', 'wc-frontend-manager' ), '<strong>' . wc_price( $data->total_earned ) . '</strong>' ),
			'placeholder'      => __( 'This is the sum of the earned commission including shipping and taxes if applicable.', 'wc-frontend-manager' ),
			'color'            => $this->chart_colors['earned'],
			'highlight_series' => 4
		);

		return $legend;
	}

	/**
	 * Output the report
	 */
	public function output_report() {
		global $WCFM;
		$ranges = array(
			'year'         => __( 'Year', 'wc-frontend-manager' ),
			'last_month'   => __( 'Last Month', 'wc-frontend-manager' ),
			'month'        => __( 'This Month', 'wc-frontend-manager' ),
			'7day'         => __( 'Last 7 Days', 'wc-frontend-manager' ),
		);

		$this->chart_colors = array(
			'view_count'          => '#00897b',
		);

		$current_range = $this->current_range;

		$this->calculate_current_range( $this->current_range );

		include( WC()->plugin_path() . '/includes/admin/views/html-report-by-date.php' );
	}

	/**
	 * Output an export link
	 */
	public function get_export_button() {
		global $WCFM;
		?>
		<a
			href="#"
			download="report-<?php echo esc_attr( $this->current_range ); ?>-<?php echo date_i18n( 'Y-m-d', current_time('timestamp') ); ?>.csv"
			class="export_csv"
			data-export="chart"
			data-xaxes="<?php esc_attr_e( 'Date', 'wc-frontend-manager' ); ?>"
			data-exclude_series="2"
			data-groupby="<?php echo $this->chart_groupby; ?>"
			data-range="<?php echo $this->current_range; ?>"
			data-custom-range="<?php echo 'custom' === $this->current_range ? $this->start_date . '-' . $this->end_date : ''; ?>"
		>
			<?php esc_html_e( 'Export CSV', 'wc-frontend-manager' ); ?>
		</a>
		<?php
	}

	/**
	 * Round our totals correctly
	 * @param  string $amount
	 * @return string
	 */
	private function round_chart_totals( $amount ) {
		global $WCFM;
		
		if ( is_array( $amount ) ) {
			return array( $amount[0], wc_format_decimal( $amount[1], wc_get_price_decimals() ) );
		} else {
			return wc_format_decimal( $amount, wc_get_price_decimals() );
		}
	}

	/**
	 * Get the main chart
	 *
	 * @return string
	 */
	public function get_main_chart() {
		global $wp_locale, $wpdb, $WCFM;
		
		// Generate Data for total earned commision
		$select = "SELECT analytics.count AS count, analytics.visited";

		$sql = $select;
		$sql .= " FROM {$wpdb->prefix}wcfm_daily_analysis AS analytics";
		$sql .= " WHERE 1=1";
		
		if( wcfm_is_vendor() ) {
			$sql .= " AND analytics.author_id = %d";
			$sql .= " AND analytics.is_store = 1";
		} else {
			$sql .= " AND analytics.is_shop = 1";
		}

		switch( $this->current_range ) {
			case 'year' :
				$sql .= " AND YEAR( analytics.visited ) = YEAR( CURDATE() )";
				break;

			case 'last_month' :
				$sql .= " AND MONTH( analytics.visited ) = MONTH( NOW() ) - 1";
				break;

			case 'month' :
				$sql .= " AND MONTH( analytics.visited ) = MONTH( NOW() )";
				break;

			case 'custom' :
				$start_date = ! empty( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : '';
				$end_date = ! empty( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : '';

				$sql .= " AND DATE( analytics.visited ) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
				break;

			case 'default' :
			case '7day' :
				$sql .= " AND DATE( analytics.visited ) BETWEEN DATE_SUB( NOW(), INTERVAL 7 DAY ) AND NOW()";
				break;
		}
			
		$sql .= " GROUP BY DATE( analytics.visited )";
			
		// Enable big selects for reports
		$wpdb->query( 'SET SESSION SQL_BIG_SELECTS=1' );
		
		if( wcfm_is_vendor() ) {
			$is_marketplece = wcfm_is_marketplace();
			if( $is_marketplece == 'wcpvendors' ) {
				$results = $wpdb->get_results( $wpdb->prepare( $sql, apply_filters( 'wcfm_current_vendor_id', WC_Product_Vendors_Utils::get_logged_in_vendor() ) ) );
			} else {
				$results = $wpdb->get_results( $wpdb->prepare( $sql, apply_filters( 'wcfm_current_vendor_id', get_current_user_id() ) ) );
			}
		} else { 
			$results = $wpdb->get_results( $sql );
		}

		// Prepare data for report
		$view_count         = $this->prepare_chart_data( $results, 'visited', 'count', $this->chart_interval, $this->start_date, $this->chart_groupby );
		
		// Encode in json format
		$chart_data = json_encode( array(
			'view_count'      => array_values( $view_count ),
		) );
		?>
		<div class="chart-container">
			<div class="analytics-chart-placeholder main"></div>
		</div>
		<script type="text/javascript">

			var analytics_chart;

			jQuery(function() {
				var analytics_data = jQuery.parseJSON( '<?php echo $chart_data; ?>' );
				var drawAnalyticsGraph = function( ) {
					var analytics_series = [
						{
							label: "<?php echo esc_js( __( 'Number of views', 'wc-frontend-manager' ) ) ?>",
							data: analytics_data.view_count,
							yaxis: 2,
							color: '<?php echo $this->chart_colors['view_count']; ?>',
							points: { show: true, radius: 6, lineWidth: 4, fillColor: '#fff', fill: true },
							lines: { show: true, lineWidth: 3, fill: false },
							shadowSize: 0
						}
					];

					analytics_chart = jQuery.plot(
						jQuery('.analytics-chart-placeholder.main'),
						analytics_series,
						{
							legend: {
								show: false
							},
							grid: {
								color: '#aaa',
								borderColor: 'transparent',
								borderWidth: 0,
								hoverable: true
							},
							xaxes: [ {
								color: '#aaa',
								position: "bottom",
								tickColor: 'transparent',
								mode: "time",
								timeformat: "<?php if ( $this->chart_groupby == 'day' ) echo '%d %b'; else echo '%b'; ?>",
								monthNames: <?php echo json_encode( array_values( $wp_locale->month_abbrev ) ) ?>,
								tickLength: 1,
								minTickSize: [1, "<?php echo $this->chart_groupby; ?>"],
								font: {
									color: "#aaa"
								}
							} ],
							yaxes: [
								{
									min: 0,
									minTickSize: 1,
									tickDecimals: 0,
									color: '#d4d9dc',
									font: { color: "#aaa" }
								},
								{
									position: "right",
									min: 0,
									tickDecimals: 2,
									alignTicksWithAxis: 1,
									color: 'transparent',
									font: { color: "#aaa" }
								}
							],
						}
					);
					
					jQuery(".analytics-chart-placeholder.main").UseWCFMaTooltip();

					jQuery('.analytics-chart-placeholder').resize();
				}
				
				var previousPoint = null, previousLabel = null;
				var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
				jQuery.fn.UseWCFMaTooltip = function () {
					jQuery(this).bind("plothover", function (event, pos, item) {
						if (item) {
							if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
								previousPoint = item.dataIndex;
								previousLabel = item.series.label;
								jQuery("#tooltip").remove();
	
								var x = item.datapoint[0];
								var y = item.datapoint[1];
	
								var color = '#555555';
								var month = new Date(x).getMonth();
	
								//console.log(item);
	
								if (item.seriesIndex == 0) {
										wcfmaShowTooltip(item.pageX,
										item.pageY,
										color,
										"<strong style='color: #555555;'>" + item.series.label + "</strong>: <strong style='color: #00897b;'>" + y + "</strong>");
								} else {
									showTooltip(item.pageX,
									item.pageY,
									color,
									"<strong>" + item.series.label + "</strong><br>" + monthNames[month] + " : <strong>" + y + "</strong>(%)");
								}
							}
						} else {
							jQuery("#tooltip").remove();
							previousPoint = null;
						}
					});
        };
 
        function wcfmaShowTooltip(x, y, color, contents) {
            jQuery('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '11px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }

				drawAnalyticsGraph();
			});
		</script>
		<?php
	}
}
