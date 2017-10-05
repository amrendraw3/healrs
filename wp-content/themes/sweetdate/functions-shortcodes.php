<?php

/*-----------------------------------------------------------------------------------*/
/*	Call to action box
 *      Create your own kleo_call_to_action() to override in a child theme.
 */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_call_to_action' ) ) {
	function kleo_call_to_action( $atts, $content = null ) {
		extract(shortcode_atts(array(
        'bg'   => '',
        'bg_size' => 'contain',
        'class' => ''
		), $atts));
		
    if ( $class != '' ) {
      $class = ' ' . $class;
    }
    $data = '';
    if ( $bg != '' ) {
      $data .= ' background: url(\'' . esc_attr( $bg ) . '\') no-repeat center center;';
    }
    if ( $bg_size != '' ) {
      $data .= ' background-size: ' . $bg_size . ';';
    }

    if ( $data != '' ) {
      $data = ' style="' . $data . '"';
    }
    
		$output ='';
		$output .= '<div id="call-to-actions">';
		$output .= '<div class="row' . $class . '"' . $data . '>';
		$output .= do_shortcode( $content );
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	add_shortcode('kleo_call_to_action', 'kleo_call_to_action');
}

/*-----------------------------------------------------------------------------------*/
/*	Rounded image
/*-----------------------------------------------------------------------------------*/

if (!function_exists('kleo_img_rounded')) {
	function kleo_img_rounded( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'src' => '',
			'class' => '',
			'link' => ''
	    ), $atts));

		if ( $link ) {
			$data= 'href="' . $link . '"';
		} else {
			$data = 'data-rel="prettyPhoto[gallery1]" href="' . $src . '"';
		}
		$output = '<div class="circle-image '.$class.'">';
		$output .= '  <a class="imagelink" ' . $data . '>
				<span class="overlay"></span>
				<span class="read"><i class="icon-'.apply_filters('kleo_img_rounded_icon','heart').'"></i></span>
				<img src="'.$src.'" alt="">
			</a>
		</div>';

	 return $output;
	}
	add_shortcode('kleo_img_rounded', 'kleo_img_rounded');
}


?>
