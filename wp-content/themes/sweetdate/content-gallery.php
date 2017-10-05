<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */
?>

<!-- Begin Article -->
<div class="row<?php if(get_cfield('centered_text') == 1) echo ' text-center'; ?>">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <div class="twelve columns">

          <?php if(get_cfield('title_checkbox') != 1): ?>
              <?php if ( is_single() ) : ?>
              <h2 class="article-title">
                  <a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
              </h2>
              <?php else : ?>
              <h2 class="article-title">
                      <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'kleo_framework' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
              </h2>
              <?php endif; // is_single() ?>
          <?php endif;?>

          <?php if(get_cfield('meta_checkbox') != 1): ?>
          <div class="article-meta clearfix">
            <ul class="link-list">
                <?php sweetdate_entry_meta(); ?>
            </ul>
          </div><!--end article-meta-->
          <?php endif;?>

      </div><!--end twelve-->


      <?php 
      $slides = get_cfield('slider');



      if (!empty($slides)) {
      ?>
      <div class="twelve columns">
        <div class="article-media clearfix">
          <div class="blog-slider">
                  <?php foreach($slides as $slide) 
                  {
                      echo '<div data-thumb="'.$slide.'">';
                        echo '<img src="'.$slide.'" alt="">';
                      echo '</div>';
                  }
                  ?>
          </div><!--end blog-slider-->
        </div><!--end article-media-->
      </div><!--end twelve-->
      <?php } ?>


      <div class="twelve columns">
        <div class="article-content">
      <?php if ( !is_single() ) : // Only display Excerpts for Search ?>

                  <?php the_excerpt(); ?>
                  <?php if (get_the_excerpt()): ?>
                      <p><a class="radius small button secondary" href="<?php the_permalink()?>"><?php _e("Continue reading", 'kleo_framework');?></a></p>
                  <?php endif; ?>
      <?php else : ?>

                  <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'kleo_framework' ) ); ?>
                  <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'kleo_framework' ), 'after' => '</div>' ) ); ?>

      <?php endif; ?>

              <?php edit_post_link( __( 'Edit', 'kleo_framework' ), '<span class="edit-link">', '</span>' ); ?>
        </div><!--end article-content-->
      </div><!--end twelve-->
    </article><!--end article-->
</div><!--end row-->
<!-- End  Article -->

<hr>      
