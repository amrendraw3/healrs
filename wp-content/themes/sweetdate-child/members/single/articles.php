<?php

/**
 * BuddyPress - Users Home
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
global $bp;

if( bp_sa_is_bp_default() ):

get_header( 'buddypress' ); ?>

    <?php get_template_part('page-parts/buddypress-profile-header');?>

    <?php get_template_part('page-parts/buddypress-before-wrap');?>

            <div id="item-nav">
                <div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
                    <ul>
                        <?php bp_get_displayed_user_nav(); ?>
                        <?php do_action( 'bp_member_options_nav' ); ?>
                    </ul>
                </div>
            </div>
            <div id="item-body">
                <?php if(bp_displayed_user_id()==bp_loggedin_user_id()):?>
                <div class="item-list-tabs no-ajax" id="subnav" role="navigation">
                    <ul class="nav nav-tabs">
                        <?php bp_get_options_nav(); ?>
                    </ul>
                </div>
                <?php endif;?>

                <?php do_action( 'bp_before_member_body' ); ?>

                <div id="articles-dir-list" class="articles dir-list">
                <?php if($bp->current_action=="new"):?>
                    <?php social_articles_load_sub_template( array('members/single/articles/new.php') ); ?>
                <?php else:?>
                    <?php social_articles_load_sub_template( array('members/single/articles/loop.php') ); ?>
                <?php endif; ?>
                </div>
                <?php do_action( 'bp_after_member_body' ); ?>
            </div>
            <?php do_action( 'bp_after_member_home_content' ); ?>

    <?php get_template_part('page-parts/buddypress-after-wrap');?>
            
<?php get_footer( 'buddypress' ); ?>

<?php
else :

    ?>
    <div id="buddypress">
        <?php do_action( 'bp_before_member_body' ); ?>


            <div id="articles-dir-list" class="articles dir-list">
                <?php if($bp->current_action=="new"):?>
                    <?php social_articles_load_sub_template( 'members/single/articles/new' ); ?>
                <?php else:?>
                    <?php social_articles_load_sub_template( 'members/single/articles/loop' ); ?>
                <?php endif; ?>
            </div>


        <?php do_action( 'bp_after_member_body' ); ?>
    </div>
<?php
endif;
?>