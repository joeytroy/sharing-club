<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://netdelight.be
 * @since      1.0.0
 *
 * @package    sharing_club
 * @subpackage sharing_club/admin/partials
 */

if ( ! defined( 'ABSPATH' ) || !is_user_logged_in()) exit; // Exit if accessed directly
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
if(!class_exists('Lending_Table')){
    require_once plugin_dir_path( __FILE__ ) . 'admin-lending-table.php';
}


    //Create an instance of our package class...
    $testListTable = new Lending_Table();
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();

    ?>
    <div class="wrap">

        <div id="icon-users" class="icon32"><br/></div>
        <h2>Lendings <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=display_lending_form&post_type=shared_item');?>"><?php _e('Add new lending', 'sharing-club')?></a></h2>
        <?php
            $message = '';
            if ('delete' === $testListTable->current_action()) {
                $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'sharing-club'), count($_REQUEST['lending'])) . '</p></div>';
            }
            echo $message;
        ?>
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="products-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']) ?>" />
            <input type="hidden" name="post_type" value="<?php echo esc_attr($_REQUEST['post_type']) ?>" />
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display() ?>
        </form>

    </div>
