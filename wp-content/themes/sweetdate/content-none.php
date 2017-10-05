<?php
/**
 * The template for displaying a "No posts found" message.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<!-- Begin Article -->
<div class="row">
  <article id="post-0" <?php post_class(); ?>>
    <div class="twelve columns">
      <h2 class="article-title"><?php _e( 'Nothing Found', 'kleo_framework' ); ?></h2>
    </div><!--end twelve-->

    <div class="twelve columns">
      <div class="article-content">
            <p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'kleo_framework' ); ?></p>
            <?php get_search_form(); ?>
      </div>
    </div>
      
  </article>
</div>


