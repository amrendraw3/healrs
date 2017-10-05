<?php
/**
 * Template Name: Full-width Page Template, No Sidebar
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */

get_header(); ?>

<!-- MAIN SECTION
================================================ -->
<section>
    <div id="main">
        
        <?php
        /**
         * Before main part - action
         */
        do_action('kleo_before_main');
        ?>
        
        <div class="row">
            <div id="main-content" class="twelve columns">


                <?php /* Start the Loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'content', 'page' ); ?>
                
                        <!-- Begin Comments -->
                        <?php comments_template( '', true ); ?>
                        <!-- End Comments -->

                <?php endwhile; ?>

            </div><!--end twelve-->

        </div><!--end row-->
  </div><!--end main-->

    <?php
    /**
     * After main part - action
     */
    do_action('kleo_after_main');
    ?>
  
</section>
<!--END MAIN SECTION-->

<?php get_footer(); ?>