<?php
/**
 * Buddypress functions
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */

//Members Search fields values
global $bp_search_fields;
$bp_search_fields = sq_option( 'bp_search_form' );

/* Load default BuddyPress AJAX functions from plugin directory */
require_once( BP_PLUGIN_DIR . '/bp-themes/bp-default/_inc/ajax.php' );

if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
	// Register buttons for the relevant component templates
	// Friends button
	if ( bp_is_active( 'friends' ) ) {
		add_action( 'bp_member_header_actions', 'bp_add_friend_button', 5 );
	}
	// Activity button
	if ( bp_is_active( 'activity' ) ) {
		add_action( 'bp_member_header_actions', 'bp_send_public_message_button', 20 );
	}
	// Messages button
	if ( bp_is_active( 'messages' ) ) {
		add_action( 'bp_member_header_actions', 'bp_send_private_message_button', 20 );
	}
	// Group buttons
	if ( bp_is_active( 'groups' ) ) {
		add_action( 'bp_group_header_actions', 'bp_group_join_button', 5 );
		add_action( 'bp_group_header_actions', 'bp_group_new_topic_button', 20 );
		add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
	}
	
	// Blog button
	if ( bp_is_active( 'blogs' ) ) {
		add_action( 'bp_directory_blogs_actions', 'bp_blogs_visit_blog_button' );
	}
}
// -----------------------------------------------------------------------------


if ( ! function_exists( 'bp_kleo_enqueue_scripts' ) ) :
	/**
	 * Enqueue theme javascript safely
	 *
	 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 * @since BuddyPress (1.5)
	 */
	function bp_kleo_enqueue_scripts() {
		
		// Enqueue the global JS - Ajax will not work without it
		wp_enqueue_script( 'dtheme-ajax-js', get_template_directory_uri() . '/custom_buddypress/_inc/global.js', array( 'jquery' ), SQUEEN_THEME_VERSION );
		
		// Add words that we need to use in JS to the end of the page so they can be translated and still used.
		$params = array(
			'my_favs'           => __( 'My Favorites', 'kleo_framework' ),
			'accepted'          => __( 'Accepted', 'kleo_framework' ),
			'rejected'          => __( 'Rejected', 'kleo_framework' ),
			'show_all_comments' => __( 'Show all comments for this thread', 'kleo_framework' ),
			'show_all'          => __( 'Show all', 'kleo_framework' ),
			'comments'          => __( 'comments', 'kleo_framework' ),
			'close'             => __( 'Close', 'kleo_framework' ),
			'view'              => __( 'View', 'kleo_framework' ),
			'mark_as_fav'       => __( 'Favorite', 'kleo_framework' ),
			'remove_fav'        => __( 'Remove Favorite', 'kleo_framework' )
		);
		wp_localize_script( 'dtheme-ajax-js', 'BP_DTheme', $params );
		
		// Maybe enqueue comment reply JS
		if ( is_singular() && bp_is_blog_page() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		// Maybe enqueue password verify JS (register page or user settings page)
		if ( bp_is_register_page() || ( function_exists( 'bp_is_user_settings_general' ) && bp_is_user_settings_general() ) ) {
			
			// Enqueue script
			wp_enqueue_script( 'bp-parent-js-password-verify', get_template_directory_uri() . '/custom_buddypress/_inc/js/password-verify.min.js', array( 'password-strength-meter' ), SQUEEN_THEME_VERSION );
		}
	}
	
	add_action( 'wp_enqueue_scripts', 'bp_kleo_enqueue_scripts' );
endif;
// -----------------------------------------------------------------------------


if ( ! function_exists( 'bp_kleo_enqueue_styles' ) ) :
	/**
	 * Enqueue theme CSS safely
	 *
	 * For maximum flexibility, BuddyPress Default's stylesheet is enqueued, using wp_enqueue_style().
	 * If you're building a child theme of bp-default, your stylesheet will also be enqueued,
	 * automatically, as dependent on bp-default's CSS. For this reason, bp-default child themes are
	 * not recommended to include bp-default's stylesheet using @import.
	 *
	 * If you would prefer to use @import, or would like to change the way in which stylesheets are
	 * enqueued, you can override bp_dtheme_enqueue_styles() in your theme's functions.php file.
	 *
	 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
	 * @see http://codex.buddypress.org/releases/1-5-developer-and-designer-information/
	 * @since BuddyPress (1.5)
	 */
	function bp_kleo_enqueue_styles() {
		
		// Register our main stylesheet
		wp_register_style( 'bp-default-main', get_template_directory_uri() . '/custom_buddypress/_inc/css/default.css', array(), SQUEEN_THEME_VERSION );
		
	}
	
	add_action( 'wp_enqueue_scripts', 'bp_kleo_enqueue_styles', 9 );
endif;
// -----------------------------------------------------------------------------

/**
 * Load Bp-Album
 *
 */
if ( ! function_exists( 'bpa_init' ) && sq_option( 'bp_album', 1 ) == 1 && ! defined( 'BP_ALBUM_IS_INSTALLED' ) ) {
	function bpa_init() {
		if ( file_exists( get_template_directory() . "/lib/bp-album/includes/bpa.core.php" ) ) {
			include_once( get_template_directory() . "/lib/bp-album/includes/bpa.core.php" );
		} else {
			include_once( FRAMEWORK_PATH . "/inc/bp-album/includes/bpa.core.php" );
		}
	}
	
	add_action( 'after_setup_theme', 'bpa_init', 10 );
}
// -----------------------------------------------------------------------------


/**
 * Pre-insert buddypress profile fields from theme options panel
 */
if ( bp_is_active( 'xprofile' ) ) :
	add_action( "wp_ajax_bp_add_custom_fields", "bp_add_custom_fields" );
endif;

global $bp_fields, $group2_args, $bp_fields_group2, $group4_args, $bp_fields_group4;
//Base fields
$bp_fields = array(
	'birthday'       => __( 'Birthday', 'kleo_framework' ),
	'sex'            => __( 'I am a', 'kleo_framework' ),
	'looking_for'    => __( 'Looking for a', 'kleo_framework' ),
	'marital_status' => __( 'Marital status', 'kleo_framework' ),
	'city'           => __( 'City', 'kleo_framework' ),
	'country'        => __( 'Country', 'kleo_framework' ),
);


//Create new Lifestyle group
$group2_args = array(
	'name' => __( 'Lifestyle', 'kleo_framework' )
);
//field types
$bp_fields_group2 = array(
	'interests'   => __( 'Interests', 'kleo_framework' ),
	'vacation'    => __( "Favorite Vacations Spot", 'kleo_framework' ),
	'ideal_date'  => __( "Ideal First Date", 'kleo_framework' ),
	'looking_for' => __( "Looking for", 'kleo_framework' ),
	'smoking'     => __( "Smoking", 'kleo_framework' ),
	'language'    => __( "Language", 'kleo_framework' ),
);

//Create new Looking for group
$group4_args      = array(
	'name' => __( 'Looking for', 'kleo_framework' )
);
$bp_fields_group4 = array(
	1 => __( 'The one thing I am most passionate about:', 'kleo_framework' ),
	2 => __( "Things I am looking for in a person are:", 'kleo_framework' )
);

function bp_add_custom_fields() {
	
	global $bp_fields, $group2_args, $bp_fields_group2, $group4_args, $bp_fields_group4;
	
	//Birthday
	if ( ! xprofile_get_field_id_from_name( $bp_fields['birthday'] ) ) {
		xprofile_insert_field(
			array(
				'field_group_id' => 1,
				'name'           => $bp_fields['birthday'],
				'can_delete'     => 1,
				'field_order'    => 2,
				'is_required'    => true,
				'type'           => 'datebox'
			)
		);
	}
	
	//I am a
	if ( ! xprofile_get_field_id_from_name( $bp_fields['sex'] ) ) {
		$sex_list_id = xprofile_insert_field(
			array(
				'field_group_id' => 1,
				'name'           => $bp_fields['sex'],
				'can_delete'     => 1,
				'field_order'    => 3,
				'is_required'    => true,
				'type'           => 'selectbox'
			)
		);
		
		$sex_type = array( __( 'Man', 'kleo_framework' ), __( 'Woman', 'kleo_framework' ) );
		
		foreach ( $sex_type as $i => $sex ) {
			xprofile_insert_field( array(
				'field_group_id' => 1,
				'parent_id'      => $sex_list_id,
				'type'           => 'selectbox',
				'name'           => $sex,
				'option_order'   => $i + 1
			) );
		}
		
	}
	
	//Looking for a
	if ( ! xprofile_get_field_id_from_name( $bp_fields['looking_for'] ) ) {
		$sex_list_id = xprofile_insert_field(
			array(
				'field_group_id' => 1,
				'name'           => $bp_fields['looking_for'],
				'can_delete'     => 1,
				'field_order'    => 4,
				'is_required'    => true,
				'type'           => 'selectbox'
			)
		);
		
		$sex_type = array( __( 'Woman', 'kleo_framework' ), __( 'Man', 'kleo_framework' ) );
		
		foreach ( $sex_type as $i => $sex ) {
			xprofile_insert_field( array(
				'field_group_id' => 1,
				'parent_id'      => $sex_list_id,
				'type'           => 'selectbox',
				'name'           => $sex,
				'option_order'   => $i + 1
			) );
		}
		
	}
	
	
	//Marital status
	if ( ! xprofile_get_field_id_from_name( $bp_fields['marital_status'] ) ) {
		$sex_list_id = xprofile_insert_field(
			array(
				'field_group_id' => 1,
				'name'           => $bp_fields['marital_status'],
				'can_delete'     => 1,
				'field_order'    => 5,
				'is_required'    => true,
				'type'           => 'selectbox'
			)
		);
		
		$sex_type = array(
			__( 'Single', 'kleo_framework' ),
			__( 'Living together', 'kleo_framework' ),
			__( 'Married', 'kleo_framework' ),
			__( 'Separated', 'kleo_framework' ),
			__( 'Divorced', 'kleo_framework' ),
			__( 'Widowed', 'kleo_framework' ),
			__( 'Prefer not to say', 'kleo_framework' )
		);
		
		foreach ( $sex_type as $i => $sex ) {
			xprofile_insert_field( array(
				'field_group_id' => 1,
				'parent_id'      => $sex_list_id,
				'type'           => 'selectbox',
				'name'           => $sex,
				'option_order'   => $i + 1
			) );
		}
		
	}
	
	//City
	if ( ! xprofile_get_field_id_from_name( $bp_fields['city'] ) ) {
		xprofile_insert_field(
			array(
				'field_group_id' => 1,
				'name'           => $bp_fields['city'],
				'can_delete'     => 1,
				'field_order'    => 6,
				'is_required'    => true,
				'type'           => 'textbox'
			)
		);
	}
	
	//Country
	if ( ! xprofile_get_field_id_from_name( $bp_fields['country'] ) ) {
		$country_list_args = array(
			'field_group_id' => 1,
			'name'           => $bp_fields['country'],
			'description'    => 'Please select your country',
			'can_delete'     => 1,
			'field_order'    => 7,
			'is_required'    => true,
			'type'           => 'selectbox',
			'order_by'       => 'default'
		);
		
		$country_list_id = xprofile_insert_field( $country_list_args );
		
		if ( $country_list_id ) {
			
			$countries = array(
				"Afghanistan",
				"Albania",
				"Algeria",
				"Andorra",
				"Angola",
				"Antigua and Barbuda",
				"Argentina",
				"Armenia",
				"Australia",
				"Austria",
				"Azerbaijan",
				"Bahamas",
				"Bahrain",
				"Bangladesh",
				"Barbados",
				"Belarus",
				"Belgium",
				"Belize",
				"Benin",
				"Bhutan",
				"Bolivia",
				"Bosnia and Herzegovina",
				"Botswana",
				"Brazil",
				"Brunei",
				"Bulgaria",
				"Burkina Faso",
				"Burundi",
				"Cambodia",
				"Cameroon",
				"Canada",
				"Cape Verde",
				"Central African Republic",
				"Chad",
				"Chile",
				"China",
				"Colombi",
				"Comoros",
				"Congo (Brazzaville)",
				"Congo",
				"Costa Rica",
				"Cote d'Ivoire",
				"Croatia",
				"Cuba",
				"Cyprus",
				"Czech Republic",
				"Denmark",
				"Djibouti",
				"Dominica",
				"Dominican Republic",
				"East Timor (Timor Timur)",
				"Ecuador",
				"Egypt",
				"El Salvador",
				"Equatorial Guinea",
				"Eritrea",
				"Estonia",
				"Ethiopia",
				"Fiji",
				"Finland",
				"France",
				"Gabon",
				"Gambia, The",
				"Georgia",
				"Germany",
				"Ghana",
				"Greece",
				"Grenada",
				"Guatemala",
				"Guinea",
				"Guinea-Bissau",
				"Guyana",
				"Haiti",
				"Honduras",
				"Hungary",
				"Iceland",
				"India",
				"Indonesia",
				"Iran",
				"Iraq",
				"Ireland",
				"Israel",
				"Italy",
				"Jamaica",
				"Japan",
				"Jordan",
				"Kazakhstan",
				"Kenya",
				"Kiribati",
				"Korea, North",
				"Korea, South",
				"Kuwait",
				"Kyrgyzstan",
				"Laos",
				"Latvia",
				"Lebanon",
				"Lesotho",
				"Liberia",
				"Libya",
				"Liechtenstein",
				"Lithuania",
				"Luxembourg",
				"Macedonia",
				"Madagascar",
				"Malawi",
				"Malaysia",
				"Maldives",
				"Mali",
				"Malta",
				"Marshall Islands",
				"Mauritania",
				"Mauritius",
				"Mexico",
				"Micronesia",
				"Moldova",
				"Monaco",
				"Mongolia",
				"Morocco",
				"Mozambique",
				"Myanmar",
				"Namibia",
				"Nauru",
				"Nepal",
				"Netherlands",
				"New Zealand",
				"Nicaragua",
				"Niger",
				"Nigeria",
				"Norway",
				"Oman",
				"Pakistan",
				"Palau",
				"Panama",
				"Papua New Guinea",
				"Paraguay",
				"Peru",
				"Philippines",
				"Poland",
				"Portugal",
				"Qatar",
				"Romania",
				"Russia",
				"Rwanda",
				"Saint Kitts and Nevis",
				"Saint Lucia",
				"Saint Vincent",
				"Samoa",
				"San Marino",
				"Sao Tome and Principe",
				"Saudi Arabia",
				"Senegal",
				"Serbia and Montenegro",
				"Seychelles",
				"Sierra Leone",
				"Singapore",
				"Slovakia",
				"Slovenia",
				"Solomon Islands",
				"Somalia",
				"South Africa",
				"Spain",
				"Sri Lanka",
				"Sudan",
				"Suriname",
				"Swaziland",
				"Sweden",
				"Switzerland",
				"Syria",
				"Taiwan",
				"Tajikistan",
				"Tanzania",
				"Thailand",
				"Togo",
				"Tonga",
				"Trinidad and Tobago",
				"Tunisia",
				"Turkey",
				"Turkmenistan",
				"Tuvalu",
				"Uganda",
				"Ukraine",
				"United Arab Emirates",
				"United Kingdom",
				"United States",
				"Uruguay",
				"Uzbekistan",
				"Vanuatu",
				"Vatican City",
				"Venezuela",
				"Vietnam",
				"Yemen",
				"Zambia",
				"Zimbabwe"
			);
			
			foreach ( $countries as $i => $country ) {
				xprofile_insert_field( array(
					'field_group_id' => 1,
					'parent_id'      => $country_list_id,
					'type'           => 'selectbox',
					'name'           => $country,
					'option_order'   => $i + 1
				) );
			}
		}
		
		
	}
	
	global $wpdb;
	
	//Create new Myself Summary group
	$group1_args = array(
		'name' => __( 'Myself Summary', 'kleo_framework' )
	);
	
	$group1_sql = "SELECT id FROM " . $wpdb->base_prefix . "bp_xprofile_groups WHERE name = '" . $group1_args['name'] . "'";
	$group1     = $wpdb->get_results( $group1_sql );
	if ( count( $group1 ) == 0 ) {
		$group1_id = xprofile_insert_field_group( $group1_args ); // group's ID
		
		$bp_fields_group1 = array( 'about_me' => __( 'About me', 'kleo_framework' ) );
		
		
		//Myself Summary profile field
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group1['about_me'] ) ) {
			xprofile_insert_field(
				array(
					'field_group_id' => $group1_id,
					'name'           => $bp_fields_group1['about_me'],
					'can_delete'     => 1,
					'field_order'    => 1,
					'is_required'    => false,
					'type'           => 'textarea'
				)
			);
		}
	}
	
	if ( ! get_group_id_by_name( $group4_args['name'] ) ) {
		$group4_id = xprofile_insert_field_group( $group4_args ); // group's ID
		
		
		//The one thing I am most passionate about:
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group4[1] ) ) {
			xprofile_insert_field(
				array(
					'field_group_id' => $group4_id,
					'name'           => $bp_fields_group4[1],
					'can_delete'     => 1,
					'field_order'    => 1,
					'is_required'    => false,
					'type'           => 'textarea'
				)
			);
		}
		
		//Things I am looking for in a person are:
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group4[2] ) ) {
			xprofile_insert_field(
				array(
					'field_group_id' => $group4_id,
					'name'           => $bp_fields_group4[2],
					'can_delete'     => 1,
					'field_order'    => 1,
					'is_required'    => false,
					'type'           => 'textarea'
				)
			);
		}
		
	}
	
	
	//Create new Lifestyle group
	
	//Interests
	$group2_sql = "SELECT id FROM " . $wpdb->base_prefix . "bp_xprofile_groups WHERE name = '" . $group2_args['name'] . "'";
	$group2     = $wpdb->get_results( $group2_sql );
	if ( count( $group2 ) == 0 ) {
		$group2_id = xprofile_insert_field_group( $group2_args ); // group's ID
		
		
		//Interests profile field
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group2['interests'] ) ) {
			$interest_id = xprofile_insert_field(
				array(
					'field_group_id' => $group2_id,
					'name'           => $bp_fields_group2['interests'],
					'can_delete'     => 1,
					'field_order'    => 1,
					'is_required'    => false,
					'type'           => 'multiselectbox'
				)
			);
			
			$interests_type = array(
				__( 'RV', 'kleo_framework' ),
				__( 'Art Enthusiast', 'kleo_framework' ),
				__( 'Billiards', 'kleo_framework' ),
				__( 'Horses/Equine', 'kleo_framework' ),
				__( 'Music', 'kleo_framework' ),
				__( 'Business', 'kleo_framework' ),
				__( 'Writing', 'kleo_framework' ),
				__( 'Snorkelling', 'kleo_framework' ),
				__( 'Tenis', 'kleo_framework' ),
				__( 'Gardening', 'kleo_framework' ),
				__( 'Dogs', 'kleo_framework' ),
				__( 'Cats', 'kleo_framework' ),
				__( 'Antiques', 'kleo_framework' ),
				__( 'Decorating', 'kleo_framework' ),
			
			
			);
			
			foreach ( $interests_type as $i => $val ) {
				xprofile_insert_field( array(
					'field_group_id' => $group2_id,
					'parent_id'      => $interest_id,
					'type'           => 'selectbox',
					'name'           => $val,
					'option_order'   => $i + 1
				) );
			}
		}
		
		//Favorite Vacations Spot
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group2['vacation'] ) ) {
			xprofile_insert_field(
				array(
					'field_group_id' => $group2_id,
					'name'           => $bp_fields_group2['vacation'],
					'can_delete'     => 1,
					'field_order'    => 2,
					'is_required'    => false,
					'type'           => 'textbox'
				)
			);
		}
		
		//Ideal First Date
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group2['ideal_date'] ) ) {
			xprofile_insert_field(
				array(
					'field_group_id' => $group2_id,
					'name'           => $bp_fields_group2['ideal_date'],
					'can_delete'     => 1,
					'field_order'    => 3,
					'is_required'    => false,
					'type'           => 'textbox'
				)
			);
		}
		
		
		//Looking for
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group2['looking_for'] ) ) {
			$looking_for_id = xprofile_insert_field(
				array(
					'field_group_id' => $group2_id,
					'name'           => $bp_fields_group2['looking_for'],
					'can_delete'     => 1,
					'field_order'    => 4,
					'is_required'    => false,
					'type'           => 'multiselectbox'
				)
			);
			
			$looking_for_type = array(
				__( 'Cyber Affair/Erotic Chat', 'kleo_framework' ),
				__( 'Serious Relationshiop', 'kleo_framework' ),
				__( 'Affair', 'kleo_framework' ),
				__( 'Just friends', 'kleo_framework' ),
			
			
			);
			
			foreach ( $looking_for_type as $i => $val ) {
				xprofile_insert_field( array(
					'field_group_id' => $group2_id,
					'parent_id'      => $looking_for_id,
					'type'           => 'selectbox',
					'name'           => $val,
					'option_order'   => $i + 1
				) );
			}
			
		}
		
		//Language
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group2['language'] ) ) {
			$language_id = xprofile_insert_field(
				array(
					'field_group_id' => $group2_id,
					'name'           => $bp_fields_group2['language'],
					'can_delete'     => 1,
					'field_order'    => 6,
					'is_required'    => false,
					'type'           => 'multiselectbox'
				)
			);
			
			$language_types = array(
				__( 'Bengali', 'kleo_framework' ),
				__( 'Cantonese', 'kleo_framework' ),
				__( 'Dutch/Africaans', 'kleo_framework' ),
				__( 'English', 'kleo_framework' ),
				__( 'French', 'kleo_framework' ),
				__( 'Farsi', 'kleo_framework' ),
				__( 'German', 'kleo_framework' ),
				__( 'Italian', 'kleo_framework' ),
				__( 'Javanese', 'kleo_framework' ),
				__( 'Korean', 'kleo_framework' ),
				__( 'Malay', 'kleo_framework' ),
				__( 'Punjabi', 'kleo_framework' ),
				__( 'Polish', 'kleo_framework' ),
				__( 'Portuguese', 'kleo_framework' ),
				__( 'Swahili', 'kleo_framework' ),
				__( 'Spanish', 'kleo_framework' ),
				__( 'Tamil', 'kleo_framework' ),
				__( 'Thai', 'kleo_framework' ),
				__( 'Turkish', 'kleo_framework' ),
				__( 'Vietnamese', 'kleo_framework' ),
			);
			
			foreach ( $language_types as $i => $val ) {
				xprofile_insert_field( array(
					'field_group_id' => $group2_id,
					'parent_id'      => $language_id,
					'type'           => 'selectbox',
					'name'           => $val,
					'option_order'   => $i + 1
				) );
			}
			
		}
		
		//Smoking
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group2['smoking'] ) ) {
			$smoking_id = xprofile_insert_field(
				array(
					'field_group_id' => $group2_id,
					'name'           => $bp_fields_group2['smoking'],
					'can_delete'     => 1,
					'field_order'    => 5,
					'is_required'    => false,
					'type'           => 'selectbox'
				)
			);
			
			$smoking_type = array(
				__( 'Never', 'kleo_framework' ),
				__( 'Casual smoker', 'kleo_framework' ),
				__( 'Daily smoker', 'kleo_framework' ),
			);
			
			foreach ( $smoking_type as $i => $val ) {
				xprofile_insert_field( array(
					'field_group_id' => $group2_id,
					'parent_id'      => $smoking_id,
					'type'           => 'selectbox',
					'name'           => $val,
					'option_order'   => $i + 1
				) );
			}
			
		}
		
	}
	
	
	//Create new Physical group
	$group3_args = array(
		'name' => __( 'Physical', 'kleo_framework' )
	);
	
	//Physical
	$group3_sql = "SELECT id FROM " . $wpdb->base_prefix . "bp_xprofile_groups WHERE name = '" . $group3_args['name'] . "'";
	$group3     = $wpdb->get_results( $group3_sql );
	if ( count( $group3 ) == 0 ) {
		$group3_id = xprofile_insert_field_group( $group3_args ); // group's ID
		
		//field types
		$bp_fields_group3 = array(
			'height'       => __( 'Height', 'kleo_framework' ),
			'hair_color'   => __( "Hair Color", 'kleo_framework' ),
			'eye_color'    => __( "Eye Color", 'kleo_framework' ),
			'body_type'    => __( "Body Type", 'kleo_framework' ),
			'weight'       => __( "Weight", 'kleo_framework' ),
			'ethnicity'    => __( "Ethnicity", 'kleo_framework' ),
			'best_feature' => __( "Best Feature", 'kleo_framework' )
		);
		
		
		//Height
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group3['height'] ) ) {
			xprofile_insert_field(
				array(
					'field_group_id' => $group3_id,
					'name'           => $bp_fields_group3['height'],
					'can_delete'     => 1,
					'field_order'    => 1,
					'is_required'    => false,
					'type'           => 'textbox'
				)
			);
		}
		
		//Weight
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group3['weight'] ) ) {
			xprofile_insert_field(
				array(
					'field_group_id' => $group3_id,
					'name'           => $bp_fields_group3['weight'],
					'can_delete'     => 1,
					'field_order'    => 1,
					'is_required'    => false,
					'type'           => 'textbox'
				)
			);
		}
		
		
		//Hair color
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group3['hair_color'] ) ) {
			$hair_color_id = xprofile_insert_field(
				array(
					'field_group_id' => $group3_id,
					'name'           => $bp_fields_group3['hair_color'],
					'can_delete'     => 1,
					'field_order'    => 2,
					'is_required'    => false,
					'type'           => 'selectbox'
				)
			);
			
			$hair_color_type = array(
				__( 'Auburn', 'kleo_framework' ),
				__( 'Black', 'kleo_framework' ),
				__( 'Blond', 'kleo_framework' ),
				__( 'Brown', 'kleo_framework' ),
				__( 'Chestnut', 'kleo_framework' ),
				__( 'Gray/White', 'kleo_framework' ),
			);
			
			foreach ( $hair_color_type as $i => $val ) {
				xprofile_insert_field( array(
					'field_group_id' => $group3_id,
					'parent_id'      => $hair_color_id,
					'type'           => 'selectbox',
					'name'           => $val,
					'option_order'   => $i + 1
				) );
			}
		}
		
		
		//Eye color
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group3['eye_color'] ) ) {
			$eye_color_id = xprofile_insert_field(
				array(
					'field_group_id' => $group3_id,
					'name'           => $bp_fields_group3['eye_color'],
					'can_delete'     => 1,
					'field_order'    => 3,
					'is_required'    => false,
					'type'           => 'selectbox'
				)
			);
			
			$eye_color_type = array(
				__( 'Black', 'kleo_framework' ),
				__( 'Blue', 'kleo_framework' ),
				__( 'Brown', 'kleo_framework' ),
				__( 'Hazel', 'kleo_framework' ),
				__( 'Gray', 'kleo_framework' ),
				__( 'Green', 'kleo_framework' ),
				__( 'Music', 'kleo_framework' )
			);
			
			foreach ( $eye_color_type as $i => $val ) {
				xprofile_insert_field( array(
					'field_group_id' => $group3_id,
					'parent_id'      => $eye_color_id,
					'type'           => 'selectbox',
					'name'           => $val,
					'option_order'   => $i + 1
				) );
			}
		}
		
		
		//Body type
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group3['body_type'] ) ) {
			$body_type_id = xprofile_insert_field(
				array(
					'field_group_id' => $group3_id,
					'name'           => $bp_fields_group3['body_type'],
					'can_delete'     => 1,
					'field_order'    => 4,
					'is_required'    => false,
					'type'           => 'selectbox'
				)
			);
			
			$body_type_type = array(
				__( 'Apple', 'kleo_framework' ),
				__( 'Pear', 'kleo_framework' ),
				__( 'Athletic', 'kleo_framework' ),
				__( 'Hourglass', 'kleo_framework' ),
				__( 'Slender', 'kleo_framework' ),
				__( 'Inverted Triangle', 'kleo_framework' ),
				__( 'Tall', 'kleo_framework' ),
				__( 'Petite', 'kleo_framework' ),
				__( 'Slender', 'kleo_framework' )
			
			);
			
			foreach ( $body_type_type as $i => $val ) {
				xprofile_insert_field( array(
					'field_group_id' => $group3_id,
					'parent_id'      => $body_type_id,
					'type'           => 'selectbox',
					'name'           => $val,
					'option_order'   => $i + 1
				) );
			}
		}
		
		
		//Ethnicity type
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group3['ethnicity'] ) ) {
			$ethnicity_id = xprofile_insert_field(
				array(
					'field_group_id' => $group3_id,
					'name'           => $bp_fields_group3['ethnicity'],
					'can_delete'     => 1,
					'field_order'    => 5,
					'is_required'    => false,
					'type'           => 'selectbox'
				)
			);
			
			$ethnicity_type = array(
				__( 'Caucasian', 'kleo_framework' ),
				__( 'Black', 'kleo_framework' ),
				__( 'Hispanic', 'kleo_framework' ),
				__( 'Middle Eastern', 'kleo_framework' ),
				__( 'Native American', 'kleo_framework' ),
				__( 'Asian', 'kleo_framework' ),
				__( 'Mixed Race', 'kleo_framework' ),
				__( 'Other Ethnicity', 'kleo_framework' )
			);
			
			foreach ( $ethnicity_type as $i => $val ) {
				xprofile_insert_field( array(
					'field_group_id' => $group3_id,
					'parent_id'      => $ethnicity_id,
					'type'           => 'selectbox',
					'name'           => $val,
					'option_order'   => $i + 1
				) );
			}
		}
		
		
		//Best feature
		if ( ! xprofile_get_field_id_from_name( $bp_fields_group3['best_feature'] ) ) {
			xprofile_insert_field(
				array(
					'field_group_id' => $group3_id,
					'name'           => $bp_fields_group3['best_feature'],
					'can_delete'     => 1,
					'field_order'    => 6,
					'is_required'    => false,
					'type'           => 'textbox'
				)
			);
		}
		
	}
	echo 'Import successful';
	die();
	
}

// -----------------------------------------------------------------------------


/*
 * Copy bp-custom.php to plugins directory
 *
 */
if ( ! get_option( 'kleo_framework' . "_bp_custom_" . SQUEEN_THEME_VERSION ) && ! file_exists( WP_PLUGIN_DIR . "/bp-custom.php" ) ) {
	if ( copy( FRAMEWORK_URL . "/inc/bp-custom.php", WP_PLUGIN_DIR . "/bp-custom.php" ) ) {
		add_option( 'kleo_framework' . "_bp_custom_" . SQUEEN_THEME_VERSION, 1 );
	}
}
// -----------------------------------------------------------------------------


/*
 * Custom group search form
 *
 */
if ( ! function_exists( 'bp_my_directory_groups_search_form' ) ) :
	function bp_my_directory_groups_search_form() {
		
		$default_search_value = bp_get_search_default_text( 'groups' );
		$search_value         = ! empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value; ?>

		<form action="" method="get" id="search-groups-form" class="custom">
			<div class="row collapse">
				<div class="nine columns">
					<label><input type="text" name="s" id="groups_search"
					              placeholder="<?php echo esc_attr( $search_value ) ?>"/></label>
				</div>
				<div class="three columns">
					<input class="button small radius secondary expand postfix" type="submit" id="groups_search_submit"
					       name="groups_search_submit" value="<?php _e( 'Search', 'kleo_framework' ) ?>"/>
				</div>
			</div>
		</form>
		
		<?php
	}
endif;


/*
 * Custom blogs search form
 *
 */
if ( ! function_exists( 'bp_my_directory_blogs_search_form' ) ) :
	function bp_my_directory_blogs_search_form() {
		
		$default_search_value = bp_get_search_default_text();
		$search_value         = ! empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value; ?>

		<form action="" method="get" id="search-blogs-form">
			<div class="row collapse">
				<div class="nine columns">
					<label><input type="text" name="s" id="blogs_search"
					              placeholder="<?php echo esc_attr( $search_value ) ?>"/></label>
				</div>
				<div class="three columns">
					<input type="submit" id="blogs_search_submit" class="button small radius secondary expand postfix"
					       name="blogs_search_submit" value="<?php _e( 'Search', 'kleo_framework' ) ?>"/>
				</div>
			</div>
		</form>
		
		<?php
	}
endif;


if ( ! function_exists( 'bp_signup_password_value' ) ):
	function bp_signup_password_value() {
		echo bp_get_signup_password_value();
	}
	
	function bp_get_signup_password_value() {
		$value = '';
		if ( isset( $_POST['signup_password'] ) ) {
			$value = $_POST['signup_password'];
		}
		
		return apply_filters( 'bp_get_signup_password_value', $value );
	}
endif;

if ( ! function_exists( 'bp_signup_password_confirm_value' ) ):
	function bp_signup_password_confirm_value() {
		echo bp_get_signup_password_confirm_value();
	}
	
	function bp_get_signup_password_confirm_value() {
		$value = '';
		if ( isset( $_POST['signup_password_confirm'] ) ) {
			$value = $_POST['signup_password_confirm'];
		}
		
		return apply_filters( 'bp_get_signup_password_confirm_value', $value );
	}
endif;


/*
 * Add Prev,Next links after breadcrumb if it is a profile page
 */
function bp_add_profile_navigation() {
	if ( bp_is_user() ): ?>

		<div class="three columns">
			<ul class="inline-list right">
				<li><?php _e( "Quick profile navigation", 'kleo_framework' ); ?> </li>
				<?php $prev = bp_prev_profile( bp_displayed_user_id() );
				if ( $prev !== "#" ) : ?>
					<li><a href="<?php echo $prev; ?>" title="<?php _e( "Previous profile", 'kleo_framework' ); ?>"><i
							class="icon-chevron-left"></i></a></li><?php endif; ?>
				<?php $next = bp_next_profile( bp_displayed_user_id() );
				if ( $next !== "#" ) : ?>
					<li><a href="<?php echo $next; ?>" title="<?php _e( "Next profile", 'kleo_framework' ); ?>"><i
							class="icon-chevron-right"></i></a></li><?php endif; ?>
			</ul>
		</div>
	
	<?php endif;
	
}

add_action( 'kleo_after_breadcrumb', 'bp_add_profile_navigation' );

/**
 * Get next profile link
 *
 * @param int $current_id Displayer user ID
 *
 * @return string User link
 */
if ( ! function_exists( 'bp_next_profile' ) ):
	function bp_next_profile( $current_id ) {
		global $wpdb;
		
		$extra = '';
		$obj   = new stdClass();
		do_action_ref_array( 'bp_pre_user_query_construct', array( &$obj ) );
		if ( isset( $obj->query_vars ) && $obj->query_vars && $obj->query_vars['exclude'] && is_array( $obj->query_vars['exclude'] ) && ! empty( $obj->query_vars['exclude'] ) ) {
			$extra = " AND us.ID NOT IN (" . implode( ",", $obj->query_vars['exclude'] ) . ")";
		}
		
		$sql = "SELECT MIN(us.ID) FROM " . $wpdb->base_prefix . "users us"
		       . " JOIN " . $wpdb->base_prefix . "bp_xprofile_data bp ON us.ID = bp.user_id"
		       . " JOIN " . $wpdb->base_prefix . "usermeta um ON um.user_id = us.ID"
		       . " WHERE um.meta_key = 'last_activity' AND us.ID > $current_id"
		       . $extra;
		
		if ( $wpdb->get_var( $sql ) && $wpdb->get_var( $sql ) !== $current_id ) {
			return bp_core_get_user_domain( $wpdb->get_var( $sql ) );
		} else {
			return '#';
		}
	}
endif;

/**
 * Get previous profile link
 *
 * @param int $current_id Displayer user ID
 *
 * @return string User link
 */
if ( ! function_exists( 'bp_prev_profile' ) ):
	function bp_prev_profile( $current_id ) {
		global $wpdb;
		
		$extra = '';
		$obj   = new stdClass();
		do_action_ref_array( 'bp_pre_user_query_construct', array( &$obj ) );
		if ( isset( $obj->query_vars ) && $obj->query_vars && $obj->query_vars['exclude'] && is_array( $obj->query_vars['exclude'] ) && ! empty( $obj->query_vars['exclude'] ) ) {
			$extra = " AND us.ID NOT IN (" . implode( ",", $obj->query_vars['exclude'] ) . ")";
		}
		
		$sql = "SELECT MAX(us.ID) FROM " . $wpdb->base_prefix . "users us"
		       . " JOIN " . $wpdb->base_prefix . "bp_xprofile_data bp ON us.ID = bp.user_id"
		       . " JOIN " . $wpdb->base_prefix . "usermeta um ON um.user_id = us.ID"
		       . " WHERE um.meta_key = 'last_activity' AND us.ID < $current_id"
		       . $extra;
		
		if ( $wpdb->get_var( $sql ) && $wpdb->get_var( $sql ) !== $current_id ) {
			return bp_core_get_user_domain( $wpdb->get_var( $sql ) );
		} else {
			return '#';
		}
	}
endif;

if ( ! function_exists( 'bp_get_online_users' ) ):
	/**
	 * Return Buddypress online users
	 * @global object $wpdb
	 *
	 * @param string $value
	 *
	 * @return integer
	 */
	function bp_get_online_users( $value = false, $field = false ) {
		global $wpdb;
		
		if ( ! $field ) {
			$default_sex = get_profile_id_by_name( 'I am a' );
			$sex         = sq_option( 'bp_sex_field', $default_sex );
			if ( $sex == 0 ) {
				$sex = $default_sex;
			}
		} else {
			$sex = get_profile_id_by_name( $field );
		}
		
		$match_ids = array();
		if ( $value ) {
			$where = " WHERE field_id = '" . $sex . "' AND value = '" . esc_sql( $value ) . "'";
			$sql   = "SELECT " . $wpdb->base_prefix . "bp_xprofile_data.user_id FROM " . $wpdb->base_prefix . "bp_xprofile_data
							$where";
			
			$match_ids = $wpdb->get_col( $sql );
			if ( ! $match_ids ) {
				$match_ids = array( 0 );
			}
		}
		$i = 0;
		
		if ( ! empty( $match_ids ) ) {
			$include_members = '&include=' . join( ",", $match_ids );
		} else {
			$include_members = '';
		}
		
		if ( bp_has_members( 'user_id=0&type=online&per_page=99999999&populate_extras=0' . $include_members ) ) :
			while ( bp_members() ) : bp_the_member();
				$i ++;
			endwhile;
		endif;
		
		return apply_filters( 'kleo_online_users_count', $i, $value );
	}
endif;

if ( ! function_exists( 'bp_member_statistics' ) ):
	function bp_member_statistics( $field = false, $value = false, $online = false ) {
		global $wpdb;
		
		// If field not set, get Gender from theme options
		if ( ! $field ) {
			$field    = 'I am a';
			$db_field = sq_option( 'bp_sex_field' );
			if ( $db_field ) {
				$field_data = new BP_XProfile_Field( $db_field );
				$field      = $field_data->name;
			}
		}
		
		if ( ! $value ) {
			return;
		}
		
		$where = " WHERE name = '" . $field . "' AND value = '" . esc_sql( $value ) . "'";
		$sql   = "SELECT " . $wpdb->base_prefix . "bp_xprofile_data.user_id FROM " . $wpdb->base_prefix . "bp_xprofile_data
				JOIN " . $wpdb->base_prefix . "bp_xprofile_fields ON " . $wpdb->base_prefix . "bp_xprofile_data.field_id = " . $wpdb->base_prefix . "bp_xprofile_fields.id
				$where";
		
		$match_ids = $wpdb->get_col( $sql );
		if ( ! $online ) {
			return count( $match_ids );
		}
		
		if ( ! $match_ids ) {
			$match_ids = array( 0 );
		}
		
		if ( ! empty( $match_ids ) ) {
			$include_members = '&include=' . join( ",", $match_ids );
		} else {
			$include_members = '';
		}
		
		$i = 0;
		if ( bp_has_members( 'user_id=0&type=online&per_page=999999999&populate_extras=0' . $include_members ) ) :
			while ( bp_members() ) : bp_the_member();
				$i ++;
			endwhile;
		endif;
		
		return apply_filters( 'bp_member_statistics', $i, $value );
	}

endif;
/*-----------------------------------------------------------------------------------*/
/*	Shortcode - Status icon
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_status_icon' ) ) {
	function kleo_status_icon( $atts, $content = null ) {
		$field = $value = $online = $type = $image = $subtitle = $class = $href = '';
		extract( shortcode_atts( array(
			'field'    => '',
			'value'    => '',
			'online'   => 'yes',
			'type'     => 'total',
			'image'    => '',
			'subtitle' => '',
			'class'    => '',
			'href'     => ''
		), $atts ) );
		
		switch ( $type ) {
			case 'total':
				$image  = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_01.png' : $image;
				$number = bp_get_total_member_count();
				break;
			case 'members_online':
				$image  = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_02.png' : $image;
				$number = bp_get_online_users();
				break;
			case 'women_online':
				$image  = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_03.png' : $image;
				$number = bp_get_online_users( "Woman" );
				break;
			case 'men_online':
				$image  = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_04.png' : $image;
				$number = bp_get_online_users( "Man" );
				break;
			case 'custom':
				$image = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_01.png' : $image;
				
				$field  = $field != '' ? $field : false;
				$value  = $value != '' ? $value : false;
				$online = $online == 'yes' ? true : false;
				$number = bp_member_statistics( $field, $value, $online );
				break;
			
			default:
				if ( $type == 'Man' ) {
					$image = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_04.png' : $image;
				} elseif ( $type == 'Woman' ) {
					$image = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_03.png' : $image;
				} else {
					$image = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_01.png' : $image;
				}
				$number = bp_get_online_users( $type );
				break;
		}
		
		$output = '<div class="status three columns mobile-one ' . $class . '">';
		$output .= '<div data-animation="pulse" class="icon">';
		if ( $href != '' ) {
			$output .= '<a href="' . $href . '">';
		}
		$output .= '<img width="213" height="149" alt="" src="' . $image . '">';
		if ( $href != '' ) {
			$output .= '</a>';
		}
		$output .= '</div>';
		$output .= '<ul class="block-grid">';
		$output .= '<li class="title">' . $number . '</li>';
		$output .= '<li class="subtitle">' . $subtitle . '</li>';
		$output .= '</ul>';
		$output .= '</div>';
		
		return $output;
	}
	
	add_shortcode( 'kleo_status_icon', 'kleo_status_icon' );
}

/*-----------------------------------------------------------------------------------*/
/*	Shortcodes
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_member_stats' ) ) {
	$field = $value = $online = '';
	function kleo_member_stats( $atts, $content = null ) {
		$field = $value = $online = '';
		extract( shortcode_atts( array(
			'field'  => '',
			'value'  => '',
			'online' => ''
		), $atts ) );
		
		$field  = $field != '' ? $field : false;
		$value  = $value != '' ? $value : false;
		$online = $online == 'yes' ? true : false;
		
		return bp_member_statistics( $field, $value, $online );
	}
	
	add_shortcode( 'kleo_member_stats', 'kleo_member_stats' );
}


if ( ! function_exists( 'kleo_total_members' ) ) {
	function kleo_total_members( $atts, $content = null ) {
		return bp_get_total_member_count();
	}
	
	add_shortcode( 'kleo_total_members', 'kleo_total_members' );
}

if ( ! function_exists( 'kleo_members_online' ) ) {
	function kleo_members_online( $atts, $content = null ) {
		$field_name = $field = '';
		extract( shortcode_atts( array(
			'field_name' => false,
			'field'      => false,
		), $atts ) );
		
		return bp_get_online_users( $field, $field_name );
	}
	
	add_shortcode( 'kleo_members_online', 'kleo_members_online' );
}

if ( ! function_exists( 'kleo_women_online' ) ) {
	function kleo_women_online( $atts, $content = null ) {
		$field = '';
		extract( shortcode_atts( array(
			'field' => 'Woman',
		), $atts ) );
		
		return bp_get_online_users( $field );
	}
	
	add_shortcode( 'kleo_women_online', 'kleo_women_online' );
}

if ( ! function_exists( 'kleo_men_online' ) ) {
	function kleo_men_online( $atts, $content = null ) {
		$field = '';
		extract( shortcode_atts( array(
			'field' => 'Man',
		), $atts ) );
		
		return bp_get_online_users( $field );
	}
	
	add_shortcode( 'kleo_men_online', 'kleo_men_online' );
}


//Top members
if ( ! function_exists( 'kleo_top_members' ) ) {
	function kleo_top_members( $atts, $content = null ) {
		$number = $class = '';
		extract( shortcode_atts( array(
			'number' => '6',
			'class'  => ''
		), $atts ) );
		
		$output = '

    <div class="section-members ' . $class . '">
        <div class="item-options" id="members-list-options">
          <a href="' . bp_get_members_directory_permalink() . '" data-id="newest" class="members-switch">' . __( "Newest", 'kleo_framework' ) . '</a>
          <a href="' . bp_get_members_directory_permalink() . '" data-id="active" class="selected members-switch">' . __( "Active", 'kleo_framework' ) . '</a>
          <a href="' . bp_get_members_directory_permalink() . '" data-id="popular" class="members-switch">' . __( "Popular", 'kleo_framework' ) . '</a>
        </div>';
		
		$output .= '<ul class="item-list kleo-bp-active-members">';
		if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&type=active&max=' . $number ) ) :
			while ( bp_members() ) : bp_the_member();
				$output .= section_members_li();
			
			endwhile;
		endif;
		$output .= '</ul>';
		
		$output .= '<ul class="item-list kleo-bp-newest-members" style="display:none;">';
		if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&type=newest&max=' . $number ) ) :
			while ( bp_members() ) : bp_the_member();
				$output .= section_members_li( 'newest' );
			
			endwhile;
		endif;
		$output .= '</ul>';
		
		$output .= '<ul class="item-list kleo-bp-popular-members" style="display:none;">';
		if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&type=popular&max=' . $number ) ) :
			while ( bp_members() ) : bp_the_member();
				$output .= section_members_li( 'popular' );
			
			endwhile;
		endif;
		$output .= '</ul>';
		
		$output .= '</div><!--end section-members-->';
		
		$output .= <<<JS
<script type="text/javascript">
jQuery(document).ready(function() {

    jQuery(".members-switch").click(function() {
        var bpMembersContext = jQuery(this).parent().parent();
        var container = "ul.kleo-bp-"+jQuery(this).attr('data-id')+"-members";

        jQuery("ul.item-list", bpMembersContext).hide();
        jQuery(".members-switch").removeClass("selected");
        jQuery(this).addClass("selected");
        jQuery(container, bpMembersContext).show(0, function() {
            jQuery(container+" li").hide().each(function (i) {
                var delayInterval = 150; // milliseconds
                jQuery(this).delay(i * delayInterval).fadeIn();
            });
        });
        return false;
    });
});

jQuery(function () {
	if (!isMobile()) {
		jQuery('.kleo-bp-active-members').hide();
		jQuery('.section-members').one('inview', function (event, visible) {
		  if (visible) {
			  var container = ".kleo-bp-active-members";
			  jQuery(container).show(0, function() {
				  jQuery(container+" li").hide().each(function (i) {
					  var delayInterval = 150; // milliseconds
					  jQuery(this).delay(i * delayInterval).fadeIn();
				  });
			  });
		  }
		});
	}

});

</script>
JS;
		
		return $output;
		
	}
	
	add_shortcode( 'kleo_top_members', 'kleo_top_members' );
}

//render member list item
function section_members_li( $type = 'active' ) {
	$output =
		'<li class="two columns mobile-two top-' . $type . '-members">
        <div class="item-avatar">
          <a href="' . bp_get_member_permalink() . '" title="' . bp_get_member_name() . '">' . bp_get_member_avatar( 'type=thumb&width=125&height=125' ) . '</a>
        </div><!--end item-avatar-->
        <div class="item">
          <div class="item-title fn"><a href="' . bp_get_member_permalink() . '" title="' . bp_get_member_name() . '">' . bp_get_member_name() . '</a></div>
          <div class="item-meta">
            <span class="activity">';
	
	switch ( $type ) {
		case 'newest':
			$output .= bp_get_member_registered();
			break;
		
		case 'popular':
			$output .= bp_get_member_total_friend_count();
			break;
		
		case 'active':
		default:
			$output .= bp_get_member_last_active();
			break;
	}
	
	$output .= '</span>
          </div>
        </div><!--end item-->
    </li>';
	
	return $output;
}

//Recent Groups
if ( ! function_exists( 'kleo_recent_groups' ) ) {
	function kleo_recent_groups( $atts, $content = null ) {
		$class = $max = '';
		extract( shortcode_atts( array(
			'class' => '',
			'max'   => 4
		), $atts ) );
		
		$output = '';
		if ( function_exists( 'bp_has_groups' ) && bp_has_groups( bp_ajax_querystring( 'groups' ) . "&type=active&max=" . apply_filters( 'kleo_recent_groups_max', $max ) ) ) :
			
			$output .= '<div id="groups" class="' . $class . '">';
			while ( bp_groups() ) : bp_the_group();
				//$members_no = preg_replace('/\D/', '', bp_get_group_member_count());
				global $groups_template;
				$members_no = $groups_template->group->total_member_count;
				$output     .= '
					<div class="six columns group-item">
						<div class="five columns">
							<div class="item-header-avatar">
								<div class="circular-item" title="">
									<small class="icon">' . __( "members", 'kleo_framework' ) . '</small>
									<input type="text" value="' . $members_no . '" class="pinkCircle">
								</div>
								' . bp_get_group_avatar( 'type=full&width=300&height=300' ) . '
							</div>
						</div>
						<h4><a href="' . bp_get_group_permalink() . '">' . bp_get_group_name() . '</a></h4>
						<p>' . char_trim( strip_tags( bp_get_group_description_excerpt() ), 60, '...' ) . '</p>
						<p><a href="' . bp_get_group_permalink() . '">' . __( "View group", 'kleo_framework' ) . ' <i class="icon-caret-right"></i></a></p>
					</div><!--end six-->';
			
			endwhile;
			$output .= '</div>';
			
			$output .= <<<JS
<script type="text/javascript">
jQuery(function () {
    if (!isMobile()) {
		jQuery(".item-header-avatar img").each(function (i) {
			jQuery(this).attr('data-src' ,jQuery(this).attr('src'));
			jQuery(this).attr('src', kleoFramework.blank_img);
		});

		jQuery('#groups').one('inview', function (event, visible) {
			if (visible) {
				var container = "#groups";

				jQuery(container+" .item-header-avatar img").each(function (i) {
					var element = jQuery(this);
					var delayInterval = 250; // milliseconds
					jQuery(this).delay(i * delayInterval).fadeOut(function() { element.attr('src' ,jQuery(this).attr('data-src')); element.fadeIn() });
				});

			}
		});
	}

});
</script>
JS;
		
		
		endif;
		
		return $output;
	}
	
	add_shortcode( 'kleo_recent_groups', 'kleo_recent_groups' );
}

//Members Shortcode
if ( ! function_exists( 'kleo_members' ) ) {
	/**
	 * Display members list
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function kleo_members( $atts, $content = null ) {
		global $bp_results;
		$show_filter = '';
		extract( shortcode_atts( array(
			'show_filter' => 'no'
		), $atts ) );
		
		$output = '<div class="search-result">';
		
		if ( $show_filter == 'yes' ) {
			$lead = '';
			if ( isset( $_GET['bs'] ) && $bp_results['users'][0] != 0 ) {
				$lead = __( "Your search returned", 'kleo_framework' ) . " " . count( $bp_results['users'] ) . " " . _n( 'member', 'members', count( $bp_results['users'] ), 'kleo_framework' );;
			}
			
			$output .= '<p class="lead">' . $lead . '</p>

			<div class="item-list-tabs" role="navigation">
				<ul>
					<li class="selected" id="members-all"><a href="' . trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() ) . '">';
			$output .= sprintf( __( 'All Members <span>%s</span>', 'buddypress' ), bp_get_total_member_count() ) . '</a></li>';
			
			if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) :
				
				$output .= '<li id="members-personal"><a href="' . bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/">' . sprintf( __( 'My Friends <span>%s</span>', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ) . '</a></li>';
			
			endif;
			$output .= '</ul>
			</div><!-- .item-list-tabs -->';
		}
		
		$output .= '<div id="members-dir-list" class="members dir-list">
			<!--Search List-->
			<div class="search-list twelve">';
		ob_start();
		locate_template( array( 'members/members-loop.php' ), true );
		$output .= ob_get_contents();
		ob_end_clean();
		$output .= '</div><!--end Search List-->
		</div><!-- #members-dir-list --></div>';
		
		return $output;
	}
	
	add_shortcode( 'kleo_members', 'kleo_members' );
}


//Members Carousel
if ( ! function_exists( 'kleo_members_carousel' ) ):
	function kleo_members_carousel( $atts, $content = null ) {
		$type = $image_size = $total = $width = $height = $class = '';
		extract( shortcode_atts( array(
			'type'       => apply_filters( 'kleo_bps_carousel_members_type', 'newest' ),
			'image_size' => 'thumb',
			'total'      => sq_option( 'buddypress_perpage' ),
			'width'      => '94',
			'height'     => '94',
			'class'      => ''
		), $atts ) );
		
		$output = '<div class="kleo_members_carousel ' . $class . '">' .
		          '<p>' .
		          '<span class="right hide-for-small">' .
		          '<a href="#" class="profile-thumbs-prev"><i class="icon-circle-arrow-left icon-large"></i></a>&nbsp;' .
		          '<a href="#" class="profile-thumbs-next"><i class="icon-circle-arrow-right icon-large"></i></a>' .
		          '</span>' .
		          '</p>' .
		          '<div class="clearfix"></div>' .
		          '<div class="carousel-profiles responsive">' .
		          '<ul class="profile-thumbs">';
		
		if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&type=' . $type . '&per_page=' . $total ) ) :
			while ( bp_members() ) : bp_the_member();
				$output .= '<li><a href="' . bp_get_member_permalink() . '">' . bp_get_member_avatar( 'type=' . $image_size . '&width=' . $width . '&height=' . $height . '&class=' ) . '</a></li>';
			endwhile;
		endif;
		$output .= '</ul>' .
		           '</div><!--end carousel-profiles-->' .
		           '</div>';
		
		return $output;
	}
	
	add_shortcode( 'kleo_members_carousel', 'kleo_members_carousel' );
endif;

/* Register form shortcode */
if ( ! function_exists( 'kleo_register_form' ) ) {
	function kleo_register_form( $atts, $content = null ) {
		$profiles = $title = $details = '';
		extract( shortcode_atts( array(
			'profiles' => 1,
			'title'    => '',
			'details'  => ''
		), $atts ) );
		
		global $bp_reg_form_show_cols, $bp_reg_form_show_carousel, $bp_reg_form_title,
		       $bp_reg_form_details;
		
		$bp_reg_form_show_cols     = true;
		$bp_reg_form_show_carousel = $profiles;
		$bp_reg_form_title         = $title;
		$bp_reg_form_details       = $details;
		
		ob_start();
		get_template_part( 'page-parts/home-register-form' );
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}
	
	add_shortcode( 'kleo_register_form', 'kleo_register_form' );
}


/* END SHORTCODES ------------------------------------------------------------*/

/**
 * Buddypress AJAX Widget
 *
 */
function widget_ajax_groups() {
	?>
	<script type="text/javascript">
		/* Buddpress Groups widget */
		jQuery(document).ready(function () {
			jQuery(".widgets div#groups-list-options a").on("click", function () {
				var a = this;
				jQuery(a).addClass("loading");
				jQuery(".widgets div#groups-list-options a").removeClass("selected");
				jQuery(this).addClass("selected");
				jQuery.post(
					ajaxurl,
					{
						action: "widget_groups_list",
						cookie: encodeURIComponent(document.cookie),
						_wpnonce: jQuery("input#_wpnonce-groups").val(),
						max_groups: jQuery("input#groups_widget_max").val(),
						filter: jQuery(this).attr("id")
					},
					function (b) {
						jQuery(a).removeClass("loading");
						groups_wiget_response(b)
					}
				);
				return false;
			})
		});
		function groups_wiget_response(a) {
			a = a.substr(0, a.length - 1);
			a = a.split("[[SPLIT]]");
			if (a[0] != "-1") {
				jQuery(".widgets ul#groups-list").fadeOut(200, function () {
					jQuery(".widgets ul#groups-list").html(a[1]);
					jQuery(".widgets ul#groups-list").fadeIn(200)
				})
			} else {
				jQuery(".widgets ul#groups-list").fadeOut(200, function () {
					var b = "<p>" + a[1] + "</p>";
					jQuery(".widgets ul#groups-list").html(b);
					jQuery(".widgets ul#groups-list").fadeIn(200)
				})
			}
		};

		/* Buddpress Members widget */
		jQuery(document).ready(function () {
			jQuery(".widgets div#members-list-options a").on("click", function () {
				var a = this;
				jQuery(a).addClass("loading");
				jQuery(".widgets div#members-list-options a").removeClass("selected");
				jQuery(this).addClass("selected");
				jQuery.post(ajaxurl, {
					action: "widget_members",
					cookie: encodeURIComponent(document.cookie),
					_wpnonce: jQuery("input#_wpnonce-members").val(),
					"max-members": jQuery("input#members_widget_max").val(),
					filter: jQuery(this).attr("id")
				}, function (b) {
					jQuery(a).removeClass("loading");
					member_wiget_response(b)
				});
				return false
			})
		});

		function member_wiget_response(a) {
			a = a.substr(0, a.length - 1);
			a = a.split("[[SPLIT]]");
			if (a[0] != "-1") {
				jQuery(".widgets ul#members-list").fadeOut(200, function () {
					jQuery(".widgets ul#members-list").html(a[1]);
					jQuery(".widgets ul#members-list").fadeIn(200)
				})
			} else {
				jQuery(".widgets ul#members-list").fadeOut(200, function () {
					var b = "<p>" + a[1] + "</p>";
					jQuery(".widgets ul#members-list").html(b);
					jQuery(".widgets ul#members-list").fadeIn(200)
				})
			}
		};


	</script>
	<?php
}

add_action( 'wp_footer', 'widget_ajax_groups' );


if ( ! function_exists( 'custom_bp_datebox' ) ):
	/**
	 * Filter year field only to allow minimum 18 year old members
	 */
	function custom_bp_datebox( $html, $type, $day, $month, $year, $field_id, $date ) {
		$current_year = date( "Y" );
		$allowed_year = $current_year - 18;
		if ( $type == 'year' ) {
			
			$html = '<option value=""' . selected( $year, '', false ) . '>----</option>';
			
			for ( $i = $allowed_year; $i >= 1920; $i -- ) {
				$html .= '<option value="' . $i . '"' . selected( $year, $i, false ) . '>' . $i . '</option>';
			}
		}
		
		return $html;
	}
endif;
add_filter( 'bp_get_the_profile_field_datebox', 'custom_bp_datebox', 10, 7 );


/***************************************************
 * Frontend Profile pictures slider init
 ***************************************************/
add_action( 'wp_footer', 'kleo_slider_js', 90 );

function kleo_slider_js() {
	if ( ! bp_is_user() ) {
		return;
	}
	?>
	<script type="text/javascript">
		function loadSlider(sliderId, left, right) {
			// Gallery carousel
			jQuery("#" + sliderId).carouFredSel({
				width: "100%",
				auto: false,
				scroll: 1,
				swipe: {
					onMouse: true,
					onTouch: true
				},
				prev: {
					button: "#" + left,
					key: "left"
				},
				next: {
					button: "#" + right,
					key: "right"
				}
			});
		}

		jQuery(window).ready(function () {
			// put here your slider ID
			var sliderID = "gallery-carousel";

			//load the slider on page load
			loadSlider(sliderID, "stanga-prev", "dreapta-next");

			//when someone calls the tab with the slider update the slider sizes
			jQuery(".sliderEvent").on("click", function () {
				jQuery(".mySlider").show();
				loadSlider(sliderID, "stanga-prev", "dreapta-next");
			});
		});
	</script>
	<?php
}

/* -----------------------------------------------------------------------------
 * END kleo_slider_js()
 */


/**
 * Return group ID by group name
 * @global object $wpdb
 *
 * @param string $name
 *
 * @return integer
 */
function get_group_id_by_name( $name ) {
	global $wpdb, $bp;
	if ( ! isset( $name ) ) {
		return false;
	}
	$table_name = $bp->profile->table_name_groups;
	
	return $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table_name} WHERE name = %s", $name ) );
}

// -----------------------------------------------------------------------------


/**
 * Return profile field ID by profile name
 * @global object $wpdb
 *
 * @param string $name
 *
 * @return integer
 */
function get_profile_id_by_name( $name ) {
	global $wpdb;
	if ( ! isset( $name ) ) {
		return false;
	}
	
	$sql = "SELECT id FROM " . $wpdb->base_prefix . "bp_xprofile_fields WHERE name = '" . $name . "'";
	
	return $wpdb->get_var( $sql );
}

// -----------------------------------------------------------------------------

/* Load Buddypress city autocomplete */
if ( sq_option( 'bp_autocomplete', 0 ) == 1 ) {
	locate_template( 'custom_buddypress/kleo-bp-city-auto.php', true );
}

/* Load Buddypress custom search */
locate_template( 'custom_buddypress/kleo-bp-search.php', true );


if ( ! function_exists( 'get_member_age' ) ):
	/**
	 * Calculate member age based on date of birth
	 *
	 * @param int $id User id to get the age for
	 *
	 * @return string
	 */
	function get_member_age( $id ) {
		$default_age_field = get_profile_id_by_name( 'Birthday' );
		$age_field         = sq_option( 'bp_age_field', $default_age_field );
		if ( $age_field == 0 ) {
			$age_field = $default_age_field;
		}
		
		if ( bp_is_active( 'xprofile' ) && xprofile_get_field_data( $age_field, $id ) ) {
			$birth = BP_XProfile_ProfileData::get_value_byid( $age_field, $id );
			
			$field = new BP_XProfile_Field ( $age_field );
			
			if ( $field->type == 'birthdate' ) {
				return strip_tags( $birth );
			}
			if ( ! empty( $birth ) ) {
				$diff = time() - strtotime( $birth );
				$age  = floor( $diff / ( 365 * 60 * 60 * 24 ) );
			}
		} else {
			$age = '';
		}
		
		return $age;
	}
endif;


/* Show Age instead of Birthdate */
if ( sq_option( 'bp_birthdate_to_age', 0 ) == 1 ) {
	
	add_filter( 'bp_get_the_profile_field_name', 'kleo_change_birth_to_age' );
	add_action( 'bp_before_profile_edit_content', 'kleo_revert_birth_to_age' );
	add_filter( 'bp_get_the_profile_field_value', 'kleo_get_field_value', 9, 3 );
	remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_format_field_value', 1, 2 );
	remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_format_field_value_by_type', 8, 2 );
	
}


function kleo_revert_birth_to_age() {
	
	remove_filter( 'bp_get_the_profile_field_name', 'kleo_change_birth_to_age' );
	
}

function kleo_change_birth_to_age( $name ) {
	
	if ( $name == __( 'Birthday', 'buddypress' ) ) {
		
		$name = __( 'Age', 'buddypress' );
		
	}
	
	return $name;
}

if ( ! function_exists( 'kleo_get_field_value' ) ) {
	function kleo_get_field_value( $value, $type, $field_id ) {
		
		$value_to_return = $value;
		$field           = new BP_XProfile_Field( $field_id );
		
		//our Age field
		$default_age_field = get_profile_id_by_name( 'Birthday' );
		$age_field         = sq_option( 'bp_age_field', $default_age_field );
		if ( $age_field == 0 ) {
			$age_field = $default_age_field;
		}
		
		if ( $type == 'datebox' && $age_field == $field_id ) {
			$value_to_return = floor( ( time() - strtotime( $value ) ) / 31556926 );
		}
		
		return $value_to_return;
	}
}

// -----------------------------------------------------------------------------


/* Members meta on members listing */
add_action( 'bp_members_meta', 'render_bp_meta' );

if ( ! function_exists( 'render_bp_meta' ) ):
	function render_bp_meta() {
		global $kleo_config;
		$output = array();
		
		if ( get_member_age( bp_get_member_user_id() ) ) {
			$output['age'] = apply_filters( 'kleo_bp_meta_after_age', get_member_age( bp_get_member_user_id() ) );
		}
		//fields to show
		$fields_arr = $kleo_config['bp_members_loop_meta'];
		
		
		//user private fields
		$private_fields = array();
		if ( function_exists( 'bp_xprofile_get_hidden_fields_for_user' ) ) {
			$private_fields = bp_xprofile_get_hidden_fields_for_user( bp_get_member_user_id() );
		}
		if ( ! empty( $private_fields ) ) {
			//get the fields ids that will be displayed on members list
			if ( false === ( $fields_id_arr = get_transient( 'kleo_bp_meta_fields' ) ) ) {
				
				$fields_id_arr = array();
				
				foreach ( $fields_arr as $val ) {
					if ( get_profile_id_by_name( $val ) ) {
						$fields_id_arr[ $val ] = get_profile_id_by_name( $val );
					}
				}
				
				set_transient( 'kleo_bp_meta_fields', $fields_id_arr, 60 * 60 * 12 );
			}
			if ( ! empty( $fields_id_arr ) ) {
				//fields that will actually display
				$show_fields = array_diff( $fields_id_arr, $private_fields );
				if ( ! empty( $show_fields ) ) {
					$fields_arr_inv = array_flip( $fields_id_arr );
					
					foreach ( $show_fields as $key => $val ):
						if ( @bp_get_member_profile_data( 'field=' . $fields_arr_inv[ $val ] ) ):
							$output[] = bp_get_member_profile_data( 'field=' . $fields_arr_inv[ $val ] );
						endif;
					endforeach;
				}
			}
		} else {
			foreach ( $fields_arr as $key => $val ):
				if ( @bp_get_member_profile_data( 'field=' . $val ) ):
					$output[] = bp_get_member_profile_data( 'field=' . $val );
				endif;
			endforeach;
		}
		
		$output = apply_filters( 'kleo_bp_meta_fields', $output );
		if ( is_array( $output ) ) {
			$output_str = '<p class="date">' . implode( " / ", $output ) . '</p>';
		} else {
			$output_str = '';
		}
		echo '<div class="search-meta">';
		echo apply_filters( 'kleo_bp_members_dir_name', '<h5 class="author"><a href="' . bp_get_member_permalink() . '">' . bp_get_member_name() . '</a></h5>' );
		echo $output_str;
		echo '</div>';
	}
endif;


/* Members details on members listing */
add_action( 'bp_directory_members_item', 'render_bp_details' );

if ( ! function_exists( 'render_bp_details' ) ):
	function render_bp_details() {
		global $kleo_config;
		
		if ( @bp_get_member_profile_data( 'field=' . apply_filters( 'kleo_bp_details_field', $kleo_config['bp_members_details_field'] ) ) ) {
			echo '<p>' . word_trim( bp_get_member_profile_data( 'field=' . apply_filters( 'kleo_bp_details_field', $kleo_config['bp_members_details_field'] ) ), 50, '...' ) . '</p>';
		}
	}
endif;


if ( ! function_exists( 'compatibility_score' ) ) :
	/**
	 * Calculate compatibility between members based on their profiles
	 *
	 * @param int|bool $userid1
	 * @param int|bool $userid2
	 *
	 * @return int
	 */
	function compatibility_score( $userid1 = false, $userid2 = false ) {
		global $kleo_config;
		
		if ( $userid1 && $userid2 ) {
			$score = $kleo_config['matching_fields']['starting_score'];
			
			//Sex match
			if ( ( isset( $kleo_config['matching_fields']['sex_match'] ) && $kleo_config['matching_fields']['sex_match'] == '1' )
			     || ! isset( $kleo_config['matching_fields']['sex_match'] )
			) {
				$field1_u1  = xprofile_get_field_data( $kleo_config['matching_fields']['sex'], $userid1 );
				$field12_u1 = xprofile_get_field_data( $kleo_config['matching_fields']['looking_for'], $userid1 );
				$field1_u2  = xprofile_get_field_data( $kleo_config['matching_fields']['sex'], $userid2 );
				$field12_u2 = xprofile_get_field_data( $kleo_config['matching_fields']['looking_for'], $userid2 );
				
				if ( $field1_u1 == $field12_u2 && $field12_u1 == $field1_u2 ) {
					$score += $kleo_config['matching_fields']['sex_percentage'];
				} //if no sex match, return the score
				else {
					if ( ( isset( $kleo_config['matching_fields']['sex_mandatory'] ) && $kleo_config['matching_fields']['sex_mandatory'] == 1 ) || ! isset( $kleo_config['matching_fields']['sex_mandatory'] ) ) {
						return $score;
					}
				}
			}
			
			//single fields match
			if ( is_array( $kleo_config['matching_fields']['single_value'] ) ) {
				foreach ( $kleo_config['matching_fields']['single_value'] as $key => $value ) {
					if ( xprofile_get_field_data( $key, $userid1 )
					     && xprofile_get_field_data( $key, $userid2 )
					     && xprofile_get_field_data( $key, $userid1 ) == xprofile_get_field_data( $key, $userid2 )
					) {
						$score += $value;
					}
				}
			}
			
			//multiple fields match
			if ( is_array( $kleo_config['matching_fields']['multiple_values'] ) ) {
				foreach ( $kleo_config['matching_fields']['multiple_values'] as $key => $value ) {
					$field1 = xprofile_get_field_data( $key, $userid1 );
					$field2 = xprofile_get_field_data( $key, $userid2 );
					if ( $field1 && $field2 ) {
						$intersect = array_intersect( (array) $field1, (array) $field2 );
						if ( count( $intersect ) >= 1 ) {
							$score += $value * count( $intersect );
						}
					}
				}
			}
			if ( $score > 100 ) {
				$score = 100;
			}
			
			return $score;
		}
	}
endif;

function kleo_bp_match_data() {
	if ( sq_option( 'bp_match', 0 ) == 1 ) {
		
		global $kleo_config;
		$match_data = sq_option( 'bp_match_data' );
		
		// var_dump($match_data);exit;
		
		$kleo_config['matching_fields']['starting_score'] = (int) sq_option( 'bp_match_start_percent', 0 );
		
		if ( sq_option( 'bp_comp_fields', 0 ) == 1 ) {
			if ( sq_option( 'bp_comp_field1', '' ) != '' && sq_option( 'bp_comp_field2', '' ) != '' ) {
				$kleo_config['matching_fields']['sex_match']      = 1;
				$kleo_config['matching_fields']['sex']            = sq_option( 'bp_comp_field1', '' );
				$kleo_config['matching_fields']['looking_for']    = sq_option( 'bp_comp_field2', '' );
				$kleo_config['matching_fields']['sex_percentage'] = (int) sq_option( 'bp_comp_percent', 0 );
				$kleo_config['matching_fields']['sex_mandatory']  = (int) sq_option( 'bp_comp_mandatory', 1 );
				
				
			}
			
		} else {
			$kleo_config['matching_fields']['sex_match'] = 0;
		}
		
		$kleo_config['matching_fields']['single_value']    = array();
		$kleo_config['matching_fields']['multiple_values'] = array();
		
		if ( isset( $match_data['single'] ) && is_array( $match_data['single'] ) ) {
			
			$single_match = array();
			
			foreach ( $match_data['single'] as $single ) {
				if ( isset( $match_data['percentages'][ $single ] ) ) {
					$single_match[ $single ] = $match_data['percentages'][ $single ];
				}
			}
			$kleo_config['matching_fields']['single_value'] = $single_match;
		}
		
		if ( isset( $match_data['multi'] ) && is_array( $match_data['multi'] ) ) {
			
			$multi_match = array();
			
			foreach ( $match_data['multi'] as $multi ) {
				if ( isset( $match_data['percentages'][ $multi ] ) ) {
					$multi_match[ $multi ] = $match_data['percentages'][ $multi ];
				}
			}
			$kleo_config['matching_fields']['multiple_values'] = $multi_match;
		}
		//print_r($kleo_config['matching_fields']);
	}
}

if ( ! is_admin() ) {
	add_action( 'init', 'kleo_bp_match_data' );
}


/* Match compatibility hook */
add_action( 'kleo_bp_before_profile_name', 'kleo_bp_compatibility_match' );

if ( ! function_exists( 'kleo_bp_compatibility_match' ) ):
	function kleo_bp_compatibility_match() {
		global $bp;
		if ( is_user_logged_in() && ! bp_is_my_profile() ):
			echo '<div class="circular-item" title="' . __( "Compatibility match", 'kleo_framework' ) . '">';
			echo '<small class="icon strong">' . __( "match", 'kleo_framework' ) . '</small>';
			echo '<input type="text" value="' . compatibility_score( $bp->loggedin_user->id, bp_displayed_user_id() ) . '" class="greenCircle">';
			echo '<span class="hearts"></span>';
			echo '</div>';
		endif;
	}
endif;

/* Fix for embeded videos widescreen */
function kleo_fix_video() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			jQuery('.activity-content .activity-inner iframe').each(function () {
				if (!jQuery(this).parent().hasClass('flex-video')) {
					jQuery(this).wrap('<div class="flex-video widescreen"></div>');
				}
			});
		});
	</script>
	<?php
}

add_action( 'wp_footer', 'kleo_fix_video' );

/**
 * Before carousel search form - ACTION
 */
add_action( 'kleo_bps_before_carousel', 'kleo_before_carousel_text' );

if ( ! function_exists( 'kleo_before_carousel_text' ) ):
	function kleo_before_carousel_text() {
		echo '<strong>' . __( "Latest registered members", 'kleo_framework' ) . '</strong>';
	}
endif;

/**
 * Buttons on member page - ACTION
 */
add_action( 'kleo_bp_header_actions', 'kleo_bp_member_buttons' );
if ( ! function_exists( 'kleo_bp_member_buttons' ) ):
	function kleo_bp_member_buttons() {
		?>

		<div class="two columns pull-two">
			<div id="item-buttons">
				<?php if ( ! is_user_logged_in() ) : ?>
					<?php if ( bp_is_active( 'friends' ) ): ?>
						<div id="friendship-button-<?php bp_displayed_user_id(); ?>"
						     class="generic-button friendship-button not_friends">
							<a data-reveal-id="login_panel" class="has-tip tip-right friendship-button not_friends add"
							   data-width="350" rel="add" id="friend-<?php bp_displayed_user_id(); ?>"
							   title="<?php _e( "Please Login to Add Friend", 'kleo_framework' ); ?>"
							   href="#"><?php _e( "Add Friend", 'kleo_framework' ); ?></a>
						</div>
					<?php endif; ?>
					<?php if ( bp_is_active( 'activity' ) ): ?>
						<div id="post-mention" class="generic-button">
							<a data-reveal-id="login_panel" class="has-tip tip-right activity-button mention"
							   data-width="350"
							   title="<?php _e( "Please Login to Send a public message", 'kleo_framework' ); ?>"
							   href="#"><?php _e( "Public Message", 'kleo_framework' ); ?></a>
						</div>
					<?php endif; ?>
					<?php if ( bp_is_active( 'messages' ) ): ?>
						<div id="send-private-message" class="generic-button">
							<a data-reveal-id="login_panel" class="has-tip tip-right send-message" data-width="350"
							   title="<?php _e( "Please Login to Send a private message", 'kleo_framework' ); ?>"
							   href="#"><?php _e( "Private Message", 'kleo_framework' ); ?></a>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<?php do_action( 'bp_member_header_actions' ); ?>
				<?php endif; ?>

			</div><!-- #item-buttons -->
		</div>
		<?php
	}
endif;

/* Load Buddypress profile tabs */
locate_template( 'custom_buddypress/class-bp-tabs.php', true );

/**
 * Define what tabs to display next to user profile
 */
global $bp_tabs;

$bp_tabs['looking-for'] = array(
	'type'  => 'cite',
	'name'  => apply_filters( 'kleo_extra_tab1', __( 'Looking for', 'kleo_framework' ) ),
	'group' => apply_filters( 'kleo_extra_tab1', __( 'Looking for', 'kleo_framework' ) ),
	'class' => 'citetab'
);

$bp_tabs['base'] = array(
	'type'  => 'regular',
	'name'  => apply_filters( 'kleo_extra_tab2', __( 'About me', 'kleo_framework' ) ),
	'group' => __( 'Base', 'kleo_framework' ),
	'class' => 'regulartab'
);

/* rtMedia tab - only if plugin installed */
if ( class_exists( 'RTMedia' ) ) {
	$bp_tabs['rtmedia'] = array(
		'type'  => 'rt_media',
		'name'  => __( 'My photos', 'kleo_framework' ),
		'class' => 'mySlider'
	);
} /* Bp-Album tab - only if plugin installed */
elseif ( function_exists( 'bpa_init' ) ) {
	$bp_tabs['bp-album'] = array(
		'type'  => 'bp_album',
		'name'  => __( 'My photos', 'kleo_framework' ),
		'class' => 'mySlider'
	);
}


/**
 * Customise tabs according to Theme options - BP Tabs
 *
 * @since 2.8
 */
function kleo_my_custom_tabs_data() {
	if ( sq_option( 'bp_tabs', 0 ) == 1 && bp_is_active( 'xprofile' ) ) {
		global $bp_tabs;
		$bp_tabs = array();
		
		$tabs_data = sq_option( 'bp_tabs_data' );
		
		if ( ! empty( $tabs_data ) && ! empty( $tabs_data['fields'] ) ) {
			foreach ( $tabs_data['fields'] as $tab ) {
				
				if ( strpos( $tab, 'group' ) !== false ) {
					$group_id = substr( $tab, 6 );
					
					$group = xprofile_get_field_group( $group_id );
					
					if ( $group ) {
						$name = $group->name;
						
						$label = isset( $tabs_data['labels'] ) && isset( $tabs_data['labels'][ $tab ] ) && $tabs_data['labels'][ $tab ] != '' ? $tabs_data['labels'][ $tab ] : $name;
						$type  = isset( $tabs_data['types'] ) && isset( $tabs_data['types'][ $tab ] ) && $tabs_data['types'][ $tab ] != '' ? $tabs_data['types'][ $tab ] : 'regular';
						
						$bp_tabs[] = array(
							'type'  => $type,
							'name'  => __( $label, 'kleo_framework' ),
							'group' => $name,
							'class' => $type . 'tab'
						);
					}
				} else {
					$label = isset( $tabs_data['labels'] ) && isset( $tabs_data['labels'][ $tab ] ) && $tabs_data['labels'][ $tab ] != '' ? $tabs_data['labels'][ $tab ] : __( 'My photos', 'kleo_framework' );
					$class = in_array( $tab, array( 'rt_media', 'bp_album' ) ) ? 'mySlider' : '';
					
					$bp_tabs[] = array(
						'type'  => $tab,
						'name'  => __( $label, 'kleo_framework' ),
						'class' => $class
					);
				}
				
			}
		}
	}
	
	
}

add_action( 'init', 'kleo_my_custom_tabs_data' );


/**
 * Displays tabs next to user image
 * @global array $bp_tabs
 */
if ( ! function_exists( 'kleo_bp_profile_tabs' ) ):
	function kleo_bp_profile_tabs() {
		global $bp_tabs;
		
		echo '<div class="seven columns">';
		new BpMembersTabs( $bp_tabs );
		echo '</div>';
	}
endif;
add_action( 'bp_after_member_header', 'kleo_bp_profile_tabs', 2 );

/* Add a new activity stream when registering with Facebook */
if ( ! function_exists( 'gaf_fb_register_activity' ) ):
	function gaf_fb_register_activity( $user_id ) {
		global $bp;
		if ( ! function_exists( 'bp_activity_add' ) ) {
			return false;
		}
		
		$userlink = bp_core_get_userlink( $user_id );
		bp_activity_add( array(
			'user_id'   => $user_id,
			'action'    => apply_filters( 'xprofile_fb_register_action', sprintf( __( '%s became a registered member', 'buddypress' ), $userlink ), $user_id ),
			'component' => 'xprofile',
			'type'      => 'new_member'
		) );
	}
endif;
add_action( 'fb_register_action', 'gaf_fb_register_activity' );


/* Show "Change photo" link over profile image */
if ( ! function_exists( 'kleo_bp_profile_photo_change' ) ):
	function kleo_bp_profile_photo_change() {
		if ( bp_is_my_profile() ) {
			echo '<div class="profile-hover-link">';
			echo '<a href="' . bp_loggedin_user_domain() . 'profile/change-avatar/#item-nav">';
			_e( "Change photo", "kleo_framework" );
			echo '</a></div>';
			
			echo '<script type="text/javascript">
		jQuery(document).ready(function() {
				jQuery("#profile").hover(
						function() {
								jQuery(".image-hover img").fadeTo(200, 0.9);
								jQuery(".image-hover .profile-hover-link").show();
						},
						function() {
								jQuery(".image-hover img").fadeTo(200, 1);
								jQuery(".image-hover .profile-hover-link").hide();
				});
		});
		</script>';
		}
	}
endif;
add_action( 'kleo_bp_after_profile_image', 'kleo_bp_profile_photo_change' );

/* Language parameter in search form */
add_action( 'kleo_bp_search_add_data', 'kleo_translated_field' );
function kleo_translated_field() {
	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		echo "<input type='hidden' name='lang' value='" . ICL_LANGUAGE_CODE . "'/>";
	}
}


if ( ! function_exists( 'kleo_bp_member_dir_view_button' ) ):
	/**
	 * Render view profile button on members directory
	 */
	function kleo_bp_member_dir_view_button() {
		?>
		<a href="<?php bp_member_permalink(); ?>" class="small button radius secondary"><i
				class="icon-angle-right"></i> <?php _e( "View profile", 'kleo_framework' ); ?></a>
		<?php
	}
endif;
add_action( 'bp_directory_members_item_last', 'kleo_bp_member_dir_view_button', 10 );


if ( ! function_exists( 'kleo_bp_member_dir_friend_button' ) ):
	/**
	 * Render add friend button on members directory
	 */
	function kleo_bp_member_dir_friend_button() {
		add_filter( 'bp_get_add_friend_button', 'kleo_change_profile_button' );
		bp_add_friend_button( bp_get_member_user_id() );
	}
endif;
if ( bp_is_active( 'friends' ) ) {
	add_action( 'bp_directory_members_item_last', 'kleo_bp_member_dir_friend_button', 11 );
}

if ( ! function_exists( 'kleo_change_profile_button' ) ):
	/**
	 * Change default BP button class and text next to profile in members directory
	 *
	 * @param array $button
	 *
	 * @return array
	 */
	function kleo_change_profile_button( $button ) {
		$is_friend = bp_is_friend( bp_get_member_user_id() );
		
		if ( empty( $is_friend ) ) {
			return false;
		}
		
		switch ( $is_friend ) {
			case "pending":
			case "is_friend":
				$button['link_text'] = '<i class="icon-minus"></i>';
				break;
			default:
				$button['link_text'] = '<i class="icon-plus"></i>';
				break;
		}
		
		$button['link_class'] = 'button small secondary radius';
		
		return $button;
	}
endif;


/* User online */
if ( ! function_exists( 'kleo_is_user_online' ) ):
	/**
	 * Check if a Buddypress member is online or not
	 * @global object $wpdb
	 *
	 * @param integer $user_id
	 * @param integer $time
	 *
	 * @return boolean
	 */
	function kleo_is_user_online( $user_id, $time = 5 ) {
		global $wpdb;
		$sql        = $wpdb->prepare( "
			SELECT u.user_login FROM $wpdb->users u JOIN $wpdb->usermeta um ON um.user_id = u.ID
			WHERE u.ID = %d
			AND um.meta_key = 'last_activity'
			AND DATE_ADD( um.meta_value, INTERVAL %d MINUTE ) >= UTC_TIMESTAMP()", $user_id, $time );
		$user_login = $wpdb->get_var( $sql );
		if ( isset( $user_login ) && $user_login != "" ) {
			return true;
		} else {
			return false;
		}
	}
endif;

if ( ! function_exists( 'kleo_online_status' ) ) {
	/**
	 * Render the html to show if a user is online or not
	 */
	function kleo_online_status() {
		if ( kleo_is_user_online( bp_get_member_user_id() ) ) {
			echo '<span class="online"></span>';
		} else {
			echo '<span class="offline"></span>';
		}
	}
	
	if ( sq_option( 'bp_online_status', 0 ) == 1 ) {
		add_action( 'bp_members_inside_avatar', 'kleo_online_status', 9 );
	}
}


//Buddypress SIDEBAR ACTION
add_action( 'wp_head', 'kleo_bp_layout' );
function kleo_bp_layout() {
	if ( ! bp_is_blog_page() ):
		if ( sq_option( 'buddypress_sidebar', 'right' ) == 'left' ) {
			add_action( 'kleo_buddypress_before_content', 'kleo_buddypress_sidebar' );
			add_filter( 'kleo_sidebar_class', create_function( '', 'return "four";' ) );
		} elseif ( sq_option( 'buddypress_sidebar', 'right' ) == 'right' ) {
			add_action( 'kleo_buddypress_after_content', 'kleo_buddypress_sidebar' );
			add_filter( 'kleo_sidebar_class', create_function( '', 'return "four";' ) );
		} elseif ( sq_option( 'buddypress_sidebar', 'right' ) == '3ll' ) {
			add_filter( 'kleo_buddypress_content_class', create_function( '', 'return "six";' ) );
			add_filter( 'kleo_sidebar_class', create_function( '', 'return "three";' ) );
			add_action( 'kleo_buddypress_before_content', 'kleo_buddypress_sidebar' );
			add_action( 'kleo_buddypress_before_content', 'kleo_extra_sidebar' );
		} elseif ( sq_option( 'buddypress_sidebar', 'right' ) == '3lr' ) {
			add_filter( 'kleo_buddypress_content_class', create_function( '', 'return "six";' ) );
			add_filter( 'kleo_sidebar_class', create_function( '', 'return "three";' ) );
			add_action( 'kleo_buddypress_before_content', 'kleo_buddypress_sidebar' );
			add_action( 'kleo_buddypress_after_content', 'kleo_extra_sidebar' );
		} elseif ( sq_option( 'buddypress_sidebar', 'right' ) == '3rr' ) {
			add_filter( 'kleo_buddypress_content_class', create_function( '', 'return "six";' ) );
			add_filter( 'kleo_sidebar_class', create_function( '', 'return "three";' ) );
			add_action( 'kleo_buddypress_after_content', 'kleo_buddypress_sidebar' );
			add_action( 'kleo_buddypress_after_content', 'kleo_extra_sidebar' );
		}
	endif;
}

//get buddypress sidebar
if ( ! function_exists( 'kleo_buddypress_sidebar' ) ):
	function kleo_buddypress_sidebar() {
		get_sidebar( 'buddypress' );
	}
endif;


/* Buddypress fix for Posting ordered list */
function kleo_fix_activity_ordered_list( $activity_allowedtags ) {
	$activity_allowedtags['ol'] = array();
	
	return $activity_allowedtags;
}

add_filter( 'bp_activity_allowed_tags', 'kleo_fix_activity_ordered_list' );


/* Terms and conditions - Register page */
add_action( 'bp_after_signup_profile_fields', 'kleo_bp_tos_register' );
//TOS in register page
function kleo_bp_tos_register() {
	if ( sq_option( 'terms_page', '#' ) != "#" ) {
		$checked       = isset( $_POST['tos_register'] ) ? 'checked="checked"' : '';
		$terms_page_id = sq_option( 'terms_page' );
		/* WPML compatibility */
		if ( function_exists( 'icl_object_id' ) ) {
			$terms_page_id = icl_object_id( $terms_page_id, 'page', true );
		}
		$terms_link = get_permalink( $terms_page_id );
		?>
		<p class="right">
			<label><input name="tos_register" id="tos_register"<?php echo $checked; ?> type="checkbox"
			              class="tos_register" data-customforms="disabled">
				<small>
					<?php printf( __( 'I agree with the <a href="%s" target="_blank"><strong>terms and conditions</strong></a>.', 'kleo_framework' ), $terms_link ); ?>
				</small>
			</label>
		</p>
		<?php
	}
}

/* Profile placeholder in menu */
add_filter( 'walker_nav_menu_start_el', 'kleo_bp_replace_placeholders' );

function kleo_bp_replace_placeholders( $output ) {
	
	if ( strpos( $output, '##profile_link##' ) !== false ) {
		
		if ( ! is_user_logged_in() ) {
			return '';
		}
		
		$logged_in_link = bp_loggedin_user_domain( '/' );
		$output         = str_replace( '##profile_link##', $logged_in_link, $output );
	}
	
	return $output;
}


/* Profile cover */

// Register the Cover Image feature for Users profiles
function sweet_cover_register_feature() {
	/**
	 * You can choose to register it for Members and / or Groups by including (or not)
	 * the corresponding components in your feature's settings. In this example, we
	 * chose to register it for both components.
	 */
	$components = array( 'groups', 'xprofile' );
	
	// Define the feature's settings
	$cover_image_settings = array(
		'name'     => 'cover_image', // feature name
		'settings' => array(
			'components'   => $components,
			'width'        => 940,
			'height'       => 225,
			'callback'     => 'sweet_default_cover_image',
			'theme_handle' => 'bp-default-main',
		),
	);
	
	
	// Register the feature for your theme according to the defined settings.
	bp_set_theme_compat_feature( bp_get_theme_compat_id(), $cover_image_settings );
}

if ( version_compare( BP_VERSION, '2.4', '>=' ) ) {
	add_action( 'bp_after_setup_theme', 'sweet_cover_register_feature' );
}

// Example of function to customize the display of the cover image
function sweet_default_cover_image( $params = array() ) {
	
	if ( empty( $params ) || ( isset( $params['cover_image'] ) && ! $params['cover_image'] ) ) {
		return;
	}
	
	/* Add body class for users with cover */
	add_filter( 'body_class', 'sweet_bp_cover_add_body_class', 30 );
	
	$cover_image = 'background-image: url(' . $params['cover_image'] . '); ' .
	               'background-repeat: no-repeat; background-size: cover; background-position: center center !important;';
	
	if ( bp_is_user() ) {
		
		return '
		/* Cover image */
		body.buddypress div#profile {
			' . $cover_image . '
		}';
	} else {
		return '
		/* Cover image */
		body.buddypress #header-cover-image  {
			' . $cover_image . '
		}';
	}
}

//inject custom class for profile pages
function sweet_bp_cover_add_body_class( $classes ) {
	$classes[] = 'is-user-profile';
	
	return $classes;
}


if ( ! function_exists( 'bp_groups_front_template_part' ) ) {
	/**
	 * Output the contents of the current group's home page.
	 *
	 * You should only use this when on a single group page.
	 *
	 * @since 2.4.0
	 */
	function bp_groups_front_template_part() {
		$located = bp_groups_get_front_template();
		
		if ( false !== $located ) {
			$slug = str_replace( '.php', '', $located );
			
			/**
			 * Let plugins adding an action to bp_get_template_part get it from here
			 *
			 * @param string $slug Template part slug requested.
			 * @param string $name Template part name requested.
			 */
			do_action( 'get_template_part_' . $slug, $slug, false );
			
			load_template( $located, true );
			
		} else if ( bp_is_active( 'activity' ) ) {
			bp_get_template_part( 'groups/single/activity' );
			
		} else if ( bp_is_active( 'members' ) ) {
			bp_groups_members_template_part();
		}
		
		return $located;
	}
}


/* Display BuddyPress Member Types Filters in Members Directory */

add_action( 'bp_members_directory_member_types', 'kleo_bp_member_types_tabs' );
function kleo_bp_member_types_tabs() {
	if ( ! bp_get_current_member_type() ) {
		$member_types = bp_get_member_types( array(), 'objects' );
		if ( $member_types ) {
			foreach ( $member_types as $member_type ) {
				if ( $member_type->has_directory == 1 ) {
					echo '<li id="members-' . esc_attr( $member_type->name ) . '" class="bp-member-type-filter">';
					echo '<a href="' . bp_get_members_directory_permalink() . 'type/' . $member_type->directory_slug . '/">' . sprintf( '%s <span>%d</span>', $member_type->labels['name'], kleo_bp_count_member_types( $member_type->name ) ) . '</a>';
					echo '</li>';
				}
			}
		}
	}
}


add_filter( 'bp_modify_page_title', 'kleo_bp_members_type_directory_page_title', 9, 4 );
function kleo_bp_members_type_directory_page_title( $title, $original_title, $sep, $seplocation ) {
	$member_type = bp_get_current_member_type();
	if ( bp_is_directory() && $member_type ) {
		$member_type = bp_get_member_type_object( $member_type );
		if ( $member_type ) {
			global $post;
			$post->post_title = $member_type->labels['name'];
			$title            = $member_type->labels['name'] . " " . $sep . " " . $original_title;
		}
	}
	
	return $title;
}


add_filter( 'bp_get_total_member_count', 'kleo_bp_get_total_member_count_member_type' );
function kleo_bp_get_total_member_count_member_type() {
	$count       = bp_core_get_active_member_count();
	$member_type = bp_get_current_member_type();
	if ( bp_is_directory() && $member_type ) {
		$count = kleo_bp_count_member_types( $member_type );
	}
	
	return $count;
}


add_filter( 'bp_get_total_friend_count', 'kleo_bp_get_total_friend_count_member_type' );
function kleo_bp_get_total_friend_count_member_type() {
	$user_id     = get_current_user_id();
	$count       = friends_get_total_friend_count( $user_id );
	$member_type = bp_get_current_member_type();
	if ( bp_is_directory() && $member_type ) {
		global $bp, $wpdb;
		$friends = $wpdb->get_results( "SELECT count(1) as count FROM {$bp->friends->table_name} bpf
        LEFT JOIN {$wpdb->term_relationships} tr ON (bpf.initiator_user_id = tr.object_id || bpf.friend_user_id = tr.object_id )
        LEFT JOIN {$wpdb->terms} t ON t.term_id = tr.term_taxonomy_id
        WHERE t.slug = '" . $member_type . "' AND (bpf.initiator_user_id = $user_id || bpf.friend_user_id = $user_id ) AND tr.object_id != $user_id AND bpf.is_confirmed = 1", ARRAY_A );
		$count   = 0;
		if ( isset( $friends['0']['count'] ) && is_numeric( $friends['0']['count'] ) ) {
			$count = $friends['0']['count'];
		}
	}
	
	return $count;
}


function kleo_bp_count_member_types( $member_type = '' ) {
	if ( ! bp_is_root_blog() ) {
		switch_to_blog( bp_get_root_blog_id() );
	}
	global $wpdb;
	$sql           = array(
		'select' => "SELECT t.slug, tt.count FROM {$wpdb->term_taxonomy} tt LEFT JOIN {$wpdb->terms} t",
		'on'     => 'ON tt.term_id = t.term_id',
		'where'  => $wpdb->prepare( 'WHERE tt.taxonomy = %s', 'bp_member_type' ),
	);
	$members_count = $wpdb->get_results( join( ' ', $sql ) );
	$members_count = wp_filter_object_list( $members_count, array( 'slug' => $member_type ), 'and', 'count' );
	$members_count = array_values( $members_count );
	if ( isset( $members_count[0] ) && is_numeric( $members_count[0] ) ) {
		$members_count = $members_count[0];
	} else {
		$members_count = 0;
	}
	restore_current_blog();
	
	return $members_count;
}


add_filter( 'bp_before_has_members_parse_args', 'kleo_bp_set_has_members_type_arg', 10, 1 );
function kleo_bp_set_has_members_type_arg( $args ) {
	$member_type  = bp_get_current_member_type();
	$member_types = bp_get_member_types( array(), 'names' );
	if ( isset( $args['scope'] ) && ! isset( $args['member_type'] ) && in_array( $args['scope'], $member_types ) ) {
		if ( $member_type ) {
			unset( $args['scope'] );
		} else {
			$args['member_type'] = $args['scope'];
		}
	}
	
	return $args;
}

add_action( 'bp_before_member_header_meta', 'kleo_bp_profile_member_type_label' );
function kleo_bp_profile_member_type_label() {
	$member_type = bp_get_member_type( bp_displayed_user_id() );
	if ( empty( $member_type ) ) {
		return;
	}
	$member_type_object = bp_get_member_type_object( $member_type );
	if ( $member_type_object ) {
		$member_type_label = '<p class="kleo_bp_profile_member_type_label">' . esc_html( $member_type_object->labels['singular_name'] ) . '</p>';
		echo apply_filters( 'kleo_bp_profile_member_type_label', $member_type_label );
	}
}

/*Make xprofile fields translable fix*/
if ( ! function_exists( 'make_x_profile_fields_name_translable' ) ):
	
	function make_x_profile_fields_name_translable( $fieldname ) {
		return __( $fieldname, 'kleo_framework' );
	}
	
	add_filter( 'bp_get_the_profile_field_name', 'make_x_profile_fields_name_translable' );

endif;