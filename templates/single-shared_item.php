<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
get_header();
?>
<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <article class="hentry">
<?php
// don't display if the club is private !
if( !is_user_logged_in() && !scwp_get_option('public')){
    echo '<h3>'.__('Sorry, you are not allowed to view this item.').'</h3>';
    get_footer();
    exit();
}
// allow access to the_content()
// check https://codex.wordpress.org/Template_Tags/get_posts#Access_all_post_data
setup_postdata( $post );
$avg = $wpdb->get_row("SELECT ROUND(AVG(comment_karma), 0) AS ratings_avg FROM ".$wpdb->comments." WHERE comment_karma>0 AND comment_post_ID = ".get_the_ID());
$comm = $wpdb->get_row("SELECT GROUP_CONCAT(NULLIF(comment_content,'') SEPARATOR '<hr />' ) comments, COUNT(*) AS total FROM ".$wpdb->comments." WHERE comment_post_ID = ".get_the_ID()." AND comment_content !='' GROUP BY comment_post_ID");
$past = $wpdb->get_row("SELECT comment_ID, comment_karma AS rating, comment_content, comment_date FROM ".$wpdb->comments." lending
WHERE user_id = ".get_current_user_id()." AND comment_post_ID = ".get_the_ID()." AND comment_date != 0 ORDER BY comment_content DESC, comment_karma DESC LIMIT 1");
$status = $wpdb->get_row("SELECT
CASE WHEN comment_date = '0000-00-00' THEN 'requested'
    WHEN comment_date_gmt > CURRENT_TIMESTAMP OR comment_date_gmt = '0000-00-00' THEN 'na'
    ELSE 'available' END availability,
IF(user_id=".get_current_user_id().", 'you', 'other') who,
$wpdb->comments.*, user_nicename FROM ".$wpdb->comments." LEFT JOIN ".$wpdb->users." ON ".$wpdb->comments.".user_id = ".$wpdb->users.".ID WHERE comment_post_ID = ".get_the_ID()." ORDER BY comment_ID DESC LIMIT 1");
// to you / other
//var_dump($status);
?>
        <div class="product-details">
            <header>
                <div class="details-header entry-header clearfix">
                <h2><?php the_title(); ?></h2>
                <!--Uncomment below to show the author-->
                <!--<h3><?php the_author(); ?></h3>-->
                <?php if(!scwp_get_option('hide_comments')){ ?>
                <div class="stars"><?php for($i=1;$i<6;$i++) echo "<span class='star ".($i<=$avg->ratings_avg?"full-star":"")."'>★</span>";?></div>
                <?php if(isset($comm)){?><a href="#reviews"><?php echo esc_html($comm->total).' '.__('review(s)', 'sharing-club') ?></a><?php } ?>
                </div>
                <?php } ?>
            </header>
            <div class="entry-content">
            <?php the_post_thumbnail('medium', array('class'=>'details-picture')) ?>
            <p><?php the_content(); ?></p>
            <!--BOOKING-->
            <?php if(@$status->availability!='available'&&$status){
                // not available
                echo '<em>';
                if($status->who=='you'){
                    if($status->availability=='requested')_e('You have requested this object.', 'sharing-club');
                    else _e('You borrowed this object.', 'sharing-club');
                } else printf(__('This object is currently borrowed by %s.', 'sharing-club'), $status->user_nicename);
                echo '</em>';
            }else if(is_user_logged_in()){
            // available ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <?php wp_nonce_field( 'booking', 'booking_nonce' ); ?>
                <input type="hidden" name="iwantit" value="1" />
                <input type="submit" value="<?php _e('Check Out Item', 'sharing-club') ?>" />
            </form>
            <?php }else{
            // suggest to register
            printf(__("Please <a href='%s'>register</a> or <a href='%s'>log in</a> to book this item.", 'sharing-club'), wp_registration_url(), wp_login_url());
            ?>

            <?php } ?>
            <!--COMMENTS-->
            <?php if(!scwp_get_option('hide_comments')){ ?>
                <?php if(isset($comm))if($comm->comments!=''){ ?><h3><?php _e('Reviews', 'sharing-club') ?></h3><div id="reviews"><?php echo stripslashes($comm->comments) ?></div><?php } ?>
                <?php if(isset($past))if($past->rating == 0 || $past->comment_content == ''){ // review & rating ?>
                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                        <h3><?php _e('Your review', 'sharing-club') ?></h3>
                        <?php wp_nonce_field( 'review', 'review_nonce' ); ?>
                        <?php if($past->comment_content == ''){ ?><textarea name="comment_content"></textarea><?php } ?>
                        <?php if($past->rating == 0){ ?>
                        <fieldset class="rating">
                            <legend><?php _e('Your rating', 'sharing-club') ?></legend>
                            <input type="radio" id="star5" name="comment_karma" value="5" /><label for="star5" title="<?php _e('Rocks'); ?>!">5 stars</label>
                            <input type="radio" id="star4" name="comment_karma" value="4" /><label for="star4" title="<?php _e('Pretty good'); ?>">4 stars</label>
                            <input type="radio" id="star3" name="comment_karma" value="3" /><label for="star3" title="<?php _e('Meh'); ?>">3 stars</label>
                            <input type="radio" id="star2" name="comment_karma" value="2" /><label for="star2" title="<?php _e('Kinda bad'); ?>">2 stars</label>
                            <input type="radio" id="star1" name="comment_karma" value="1" /><label for="star1" title="<?php _e('Sucks big time'); ?>">1 star</label>
                        </fieldset>
                        <?php } ?>
                        <input type="hidden" name="comment_ID" value="<?php echo $past->comment_ID ?>" />
                        <p class="clear"><br /><input type="submit" value="<?php _e('Send', 'sharing-club')?>" /></p>
                    </form>
                <?php } ?>
            <?php } ?>
                <br /><p class="clear">&laquo; <a href="<?php echo get_post_type_archive_link( get_post_type() ); ?>"><?php _e('Back to list', 'sharing-club') ?></a></p>
            </div>
        </div>
        </article>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer();?>
