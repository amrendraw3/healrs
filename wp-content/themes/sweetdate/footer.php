<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<?php do_action( 'kleo_after_page' ) ?>

<?php if( is_active_sidebar('footer-level-2') ) : ?>
<!-- TESTIMONIALS SECTION ================================================ -->
<section class="with-top-border">
  	<div class="row">
    	<div class="twelve columns">
        <?php
        if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-level-2')): 
        endif;
        ?>
      </div>
    </div>
</section>
<!--END TESTIMONIALS SECTION-->
<?php endif; ?>


<?php if( is_active_sidebar('footer-level1-1') || is_active_sidebar('footer-level1-2')  ) : ?>
<!-- SUPPORT & NEWSLETTER SECTION ================================================ -->
<section>
  <div id="support">
    <div class="row">
      <div class="four columns">
        <?php
        if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-level1-1')): 
        endif;
        ?>
      </div>
      
      <div class="eight columns">
        <?php
        if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-level1-2')): 
        endif;
        ?>
      </div>
    </div><!--end row-->
  </div><!--end support-->
</section>
<!--END SUPPORT & NEWSLETTER SECTION-->
<?php endif; ?>



<!-- FOOTER SECTION
================================================ -->
<footer>
  <div id="footer">


    <div class="footer-banner">
        <img src="./wp-content/uploads/2017/09/sticky.png">
    </div>

<div class="row">
  
    <p class="text-footer">Subscribe to our Newsletter</p>
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Enter Your Email Address">

      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Subscribe</button>
      </span>
    </div><!-- /input-group -->
  
</div><!-- /.row -->












    <div class="row">
    
        <div class="">
            <div class="widgets-container footer_location">
            <?php
            if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-2')): 
            endif;
            ?>
            </div>
        </div>

    </div>



    <div class="container footer-social">
        <a href="#"><li class="fa fa-facebook"></li></a>
        <a href="#"><li class="fa fa-linkedin"></li></a>
        <a href="#"><li class="fa fa-twitter"></li></a>
    </div>

      <div class="twelve columns copyright">
        <?php do_action('kleo_footer_text');?>
      </div>
  </div><!--end footer-->
</footer>
<!--END FOOTER SECTION-->













<!-- POP-UP MODAL FORMS
================================================ -->
<!--Login panel-->
<?php get_template_part('page-parts/general-login-modal');?>
<!--end login panel-->

<!-- Register panel -->
<?php if(get_option('users_can_register')) { ?>
    <?php get_template_part('page-parts/general-register-modal');?>
<?php } ?>
<!-- end register panel -->

<!-- Forgot panel -->
<?php get_template_part('page-parts/general-forgot-modal');?>
<!-- end forgot panel -->

<!-- end login register stuff -->

<p id="btnGoUp"><?php _e("Go up", 'kleo_framework');?></p>

</div><!--end page-->  

<!--END POP-UP MODAL FORMS-->
  
<?php do_action('kleo_after_footer');?>

  <!-- Analytics -->
<?php echo sq_option('analytics'); ?>

<?php wp_footer(); ?>
</body>
</html>
