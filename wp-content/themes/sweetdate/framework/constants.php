<?php
/*
 * Theme constants
 */

define('KLEO_DOMAIN','sweetdate');
define('SQUEEN_THEME_VERSION', "2.9.11");
define('THEME_PATH', get_stylesheet_directory());
define('THEME_URL', get_stylesheet_directory_uri());
define('FRAMEWORK_URL', dirname(__FILE__));
define('FRAMEWORK_PATH', dirname(__FILE__));
define('FRAMEWORK_HTTP', get_template_directory_uri()."/framework");

define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

//Theme options
if (!function_exists('sq_option'))
{
    
    //array with theme options
    $sq_options = get_option(KLEO_DOMAIN);
    
    /**
     * Function to get options in front-end
     * @param int $option The option we need from the DB
     * @param string $default If $option doesn't exist in DB return $default value
     * @return string
     */
    function sq_option($option=false, $default=false)
    {
        if($option === FALSE)
        {
            return FALSE;
        }
        global $sq_options;

        
        if(isset($sq_options[$option]) && $sq_options[$option] !== '')
        {
            return $sq_options[$option];
        }
        else
        {
            return $default;
        }
        
    }
}

?>
