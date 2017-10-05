<?php



/**
 * Theme Framework main class
 *
 * @author SeventQueen
 */
class SQueen {
    
    private $tgm_plugins;
    private $custom_css;
    
    public function __construct($args=NULL) {
        
        if ($args !== NULL)
        {
            //load shortcodes generator
            require_once FRAMEWORK_URL. '/shortcodes/kleo-shortcodes.php';

            //load post types class
            require_once FRAMEWORK_URL. '/classes/post_types.php';
            new Post_types();
            
            //load required plugins if the theme needs to
            if ( is_admin() && ! empty($args['required_plugins']))
            {
                $this->tgm_plugins = $args['required_plugins'];
                require_once FRAMEWORK_URL. '/classes/tgm-plugin-activation.php';
                add_action( 'tgmpa_register', array($this,'required_plugins') );
            }
            
            //load multiple sidebars plugin
            if(!class_exists('sidebar_generator'))
               require_once FRAMEWORK_URL. '/classes/multiple_sidebars.php';
            
            //load facebook login if it is enabled in theme options
            if (sq_option('facebook_login', 1) == 1 && sq_option('fb_app_id', '') !== '')
                 require_once FRAMEWORK_URL. '/functions/facebook_login.php';
            
            //widgets
            require_once FRAMEWORK_URL. '/widgets/widget_twitter_1.1.php';
            require_once FRAMEWORK_URL. '/widgets/widget_recent_posts.php';
            require_once FRAMEWORK_URL. '/widgets/widget_about_us.php';
            require_once FRAMEWORK_URL. '/widgets/widget_support_box.php';
            require_once FRAMEWORK_URL. '/widgets/widget_mailchimp.php';
            require_once FRAMEWORK_URL. '/widgets/widget_testimonials.php';
            
            //envato theme update
            add_filter("pre_set_site_transient_update_themes", array(&$this,"themeforest_themes_update"));
        }
    }
    
    function required_plugins() {
            // Change this to your theme text domain, used for internationalising strings
            $theme_text_domain = 'kleo_framework';

            /**
             * Array of configuration settings. Amend each line as needed.
             * If you want the default strings to be available under your own theme domain,
             * leave the strings uncommented.
             * Some of the strings are added into a sprintf, so see the comments at the
             * end of each line for what each argument will be.
             */
            $config = array(
                    'domain'            => 'tgmpa-' . SQUEEN_THEME_VERSION,           // Text domain - likely want to be the same as your theme.
                    'default_path'      => '',                           // Default absolute path to pre-packaged plugins
                    //'parent_menu_slug'  => 'themes.php',         // Default parent menu slug
                    //'parent_url_slug'   => 'themes.php',         // Default parent URL slug
                    'menu'              => 'install-required-plugins',   // Menu slug
                    'has_notices'       => true,                         // Show admin notices or not
                    'is_automatic'      => true,            // Automatically activate plugins after installation or not
                    'message'           => '',               // Message to output right before the plugins table
                    'strings'           => array(
                            'page_title'                                => __( 'Install Required Plugins', $theme_text_domain ),
                            'menu_title'                                => __( 'Install Plugins', $theme_text_domain ),
                            'installing'                                => __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
                            'oops'                                      => __( 'Something went wrong with the plugin API.', $theme_text_domain ),
                            'notice_can_install_required'               => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
                            'notice_can_install_recommended'            => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
                            'notice_cannot_install'                     => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
                            'notice_can_activate_required'              => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
                            'notice_can_activate_recommended'           => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
                            'notice_cannot_activate'                    => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
                            'notice_ask_to_update'                      => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
                            'notice_cannot_update'                      => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
                            'install_link'                              => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
                            'activate_link'                             => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
                            'return'                                    => __( 'Return to Required Plugins Installer', $theme_text_domain ),
                            'plugin_activated'                          => __( 'Plugin activated successfully.', $theme_text_domain ),
                            'complete'                                  => __( 'All plugins installed and activated successfully. %s', $theme_text_domain ) // %1$s = dashboard link
                    )
            );

            tgmpa( $this->tgm_plugins, $config );

    }
    
    
    public function add_css($data)
    {
        $this->custom_css .= $data;
    }
    
    public function render_css() {
        
        echo "<style>\n";
        echo $this->custom_css;
        
        if(sq_option('quick_css'))
        {
            echo sq_option('quick_css')."\n";
        }
        echo "\n</style>\n";
    }
    
    /**
     * Add css for background option
     * @param string $option Theme option to get
     * @param string $css_elements Css elements to apply style
     */
    public function add_bg_css($option, $css_elements)
    {
        $db_option = sq_option($option);
        switch ($db_option['type']) {
            case 'pattern':
                $this->add_css($css_elements.' {background:'.$db_option['color'].' url("'.get_template_directory_uri().'/assets/images/patterns/'.$db_option['pattern'].'_pattern.gif"); }');
                break;
            case 'image':
                $this->add_css($css_elements.' {background-color:'.$db_option['color'].'; background-image: url("'.$db_option['image'].'"); background-position: '.$db_option['img_horizontal'].' '.$db_option['img_vertical'].'; background-repeat: '.$db_option['img_repeat'].';background-attachment:'.$db_option['img_attachment'].'; background-size:'.$db_option['img_size'].' }');
                break;
            case 'color':
                $this->add_css($css_elements.' {background:'.$db_option['color'].'; }');
                break;
            default:
                break;
        }
    }
    
    /**
     * Get css for background option
     * @param string $option Theme option to get
     * @param string $css_elements Css elements to apply style
     */
    public function get_bg_css($option, $css_elements)
    {
        $output = '';
        $db_option = sq_option($option);
        switch ($db_option['type']) {
            case 'pattern':
                $output .= $css_elements.' {background:'.$db_option['color'].' url("'.get_template_directory_uri().'/assets/images/patterns/'.$db_option['pattern'].'_pattern.gif"); }';
                break;
            case 'image':
                $output .= $css_elements.' {background-color:'.$db_option['color'].'; background-image: url("'.$db_option['image'].'"); background-position: '.$db_option['img_horizontal'].' '.$db_option['img_vertical'].'; background-repeat: '.$db_option['img_repeat'].';background-attachment:'.$db_option['img_attachment'].'; background-size:'.$db_option['img_size'].' }';
                break;
            case 'color':
                $output .= $css_elements.' {background:'.$db_option['color'].'; }';
                break;
						case 'none':
            default:
								$output .= $css_elements.' {background:none; }';
                break;
        }
        
        return $output;
    }
    
    
    /**
     * Add css for typography option
     * @param string $option Theme option to get
     * @param string $css_elements Css elements to apply style
     */
    public function add_typography_css($option)
    {
			$db_option = sq_option($option);

			foreach($db_option as $k => $v)
			{
				$family = explode(':', $v['font']);
				$font = $v['font']. apply_filters('kleo_gfont_extra_params','');

				//Load Google Font
				$normal_fonts = array("Arial", "Georgia", "Verdana", "Tahoma", "Times New Roman");
				if (!in_array($family[0],$normal_fonts))
				{
					add_action( 'wp_enqueue_scripts', create_function( '', "wp_register_style(sanitize_title('$font'), '//fonts.googleapis.com/css?family=$font', array(), '', 'all' );wp_enqueue_style( sanitize_title('$font'));" ) );
				}

        if ( $k == 'body' ) {
          $this->add_css("body, p, div {font: ".$v['style']." ".$v['size'] ." '" .$family[0]."';}");
        } else {
          $this->add_css($k." {font: ".$v['style']." ".$v['size'] ." '" .$family[0]."'; color: ".$v['color'].";}");
        }
			}
    }
    
    /**
     * 
     * @param type $updates
     * @return type
     */
    public function themeforest_themes_update($updates) {
        if (isset($updates->checked)) {
            if (!class_exists('Pixelentity_Themes_Updater')) {
                require_once(FRAMEWORK_PATH ."/inc/pixelentity-themes-updater/class-pixelentity-themes-updater.php");
            }
            $username = sq_option('tf_username',false) ? sq_option('tf_username') : null;
            $apikey = sq_option('tf_apikey', false) ? sq_option('tf_apikey') : null;

            $updater = new Pixelentity_Themes_Updater($username,$apikey);
            $updates = $updater->check($updates);
        }
        return $updates;
    }
    
    
}

?>
