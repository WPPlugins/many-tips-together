<?php
/**
 * Media Library re-attach and detach
 *
 * @package    ManyTipsTogether
 * @subpackage MTT_Hook_Media
 */

class MTT_Media_Detach_Reattach
{
	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct()
	{
        add_action(
            "manage_media_custom_column", 
            array( $this, 'reattach_column_display' ), 0, 2
        );
        add_filter(
            "manage_upload_columns", 
            array( $this, 'reattach_column_redefine' )
        );
        add_filter( 
            'manage_upload_sortable_columns', 
            array( $this, 'reattach_column_sortable' )
        );
        add_action( 'admin_menu', array( $this, '_admin_menu' ) );
        add_action( 'admin_footer-upload.php', array( $this, '_admin_footer' ) );
        add_action( 'load-upload.php', array( $this, 'bulk_action' ) );
	}


    /**
	 * Add better attach column to wp-admin/upload.php
	 * 
	 * @param array $columns
	 * @return type
	 */
	public function reattach_column_redefine( $columns )
	{
		unset( $columns['parent'] );
		$columns['better_parent'] = __( "Attached to", 'mtt' );
		return $columns;
	}

    
    /**
     * Enable sorting for better attachment column
     * @param array $columns
     * @return array
     */
    public function reattach_column_sortable( $columns )
    {	
        $columns['better_parent'] = 'parent';
        return $columns;
    }

    
	/**
	 * Display better attach column in wp-admin/upload.php
	 * http://wpengineer.com/2165/small-extension-for-the-media-library/
	 * 
	 * @param type $column_name
	 * @param type $id
	 * @return type
	 */
	public function reattach_column_display( $column_name, $id )
	{
		$post = get_post( $id );
		if( 'better_parent' != $column_name )
			return;

		if( $post->post_parent > 0 )
		{
			$title = __( '(Unnattached)', 'mtt' );
            $url_unattach = false;
			if( get_post( $post->post_parent ) )
			{
				$title = _draft_or_post_title( $post->post_parent );
                $url_unattach = wp_nonce_url(
                    admin_url('admin.php?page=unattach&noheader=true&post_id=' . $post->ID), 
                    'mtt_nonce' 
                );
			}
			?>
			<strong>
				<a href="<?php echo get_edit_post_link( $post->post_parent ); ?>">
			<?php echo $title ?>
				</a>
			</strong>, 
			<?php echo get_the_time( __( 'Y/m/d' ) ); ?>
			<br/>
			<a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;" href="#the-list">
			<?php _e( 'Re-Attach', 'mtt' ); ?>
			</a>
            <?php
            if( $url_unattach ): ?>
			<br/>
            <a href="<?php echo esc_url( $url_unattach ); ?>"
              title="<?php echo __( 'Unattach this media item.', 'mtt'); ?>"><?php echo __( 'Unattach', 'mtt'); ?>
            </a>
			<?php
            endif;
		}
		else
		{
			?>
			<?php _e( '(Unattached)', 'mtt' ); ?><br/>
			<a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;"
			   href="#the-list">
			<?php _e( 'Attach', 'mtt' ); ?>
			</a>
			<?php
		}
	}

    /**
     * Handler for unattaching a single post
     */
    public function _admin_menu()
    {
        if ( current_user_can( 'upload_files' ) ) 
            add_submenu_page(
                'admin.php', 
                'Unattach Media', 
                'Unattach', 
                'upload_files', 
                'unattach', 
                array( $this, 'unattach_attachment' )
            );

    }
    /**
     * Unattach a single post
     * 
     * @global object $wpdb
     * @author davidnde http://wordpress.org/plugins/unattach-and-re-attach-attachments
     */
    public function unattach_attachment() 
    {
        check_admin_referer( 'mtt_nonce' );
        global $wpdb;

        if (!empty($_REQUEST['post_id'])) {
            $wpdb->update(
                $wpdb->posts, 
                array('post_parent'=>0),
                array(
                    'id' => (int)$_REQUEST['post_id'], 
                    'post_type' => 'attachment'
                )
            );
        }

        wp_redirect( admin_url('upload.php') );
        exit;
    }


    /**
     * Inject Bulk Actions
     * 
     * @author davidnde http://wordpress.org/plugins/unattach-and-re-attach-attachments
     */
    public function _admin_footer()
    {
        ?>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        
        $('<option>').val('unattach').text('<?php _e('Unattach')?>').appendTo("select[name='action']");
        $('<option>').val('reattach').text('<?php _e('Re-Attach')?>').appendTo("select[name='action']");
        $('<option>').val('unattach').text('<?php _e('Unattach')?>').appendTo("select[name='action2']");
        $('<option>').val('reattach').text('<?php _e('Re-Attach')?>').appendTo("select[name='action2']");

        $('#doaction, #doaction2').click(function(e){
            $('select[name^="action"]').each(function(){
                if ( $(this).val() == 'reattach' ) {
                    e.preventDefault();
                    findPosts.open();
                }
            });
        });
    });
</script>
        <?php
    }
    
   
    /**
     * Implements a bulk action for unattaching items in bulk.
     * 
     * @see http://wordpress.stackexchange.com/questions/91874/how-to-make-custom-bulk-actions-work-on-the-media-upload-page
     * @see http://www.skyverge.com/blog/add-custom-bulk-action/
     * @copiedfrom http://wordpress.org/plugins/unattach-and-re-attach-attachments
     * @global object $wpdb
     * @return void
     */
    public function bulk_action() {

        //  ***if($post_type == 'attachment') {  REPLACE WITH:
        if ( !isset( $_REQUEST['detached'] ) ) {

            // get the action
            $wp_list_table = _get_list_table('WP_Media_List_Table');
            $action = $wp_list_table->current_action();

            $allowed_actions = array("unattach");
            if(!in_array($action, $allowed_actions)) return;

            // security check
            //  ***check_admin_referer('bulk-posts'); REPLACE WITH:
            check_admin_referer('bulk-media');

            // make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
            if(isset($_REQUEST['media'])) {
                $post_ids = array_map('intval', $_REQUEST['media']);
            }

            if(empty($post_ids)) return;

            // this is based on wp-admin/edit.php
            $sendback = remove_query_arg( array('unattached', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
            if ( ! $sendback )
                $sendback = admin_url( "upload.php?post_type=$post_type" );

            $pagenum = $wp_list_table->get_pagenum();
            $sendback = add_query_arg( 'paged', $pagenum, $sendback );

            switch($action) {
                case 'unattach':
                    global $wpdb;

                    // if we set up user permissions/capabilities, the code might look like:
                    //if ( !current_user_can($post_type_object->cap->export_post, $post_id) )
                    //  wp_die( __('You are not allowed to unattach this post.') );
                    if ( !is_admin() )
                        wp_die( __('You are not allowed to unattach this post.') );

                    $unattached = 0;
                    foreach( $post_ids as $post_id ) {
                        // Alter post to unattach media file.
                        if ( $wpdb->update($wpdb->posts, array('post_parent'=>0), array('id' => (int)$post_id, 'post_type' => 'attachment')) === false)
                            wp_die( __('Error unattaching post.') );
                        $unattached++;
                    }

                    $sendback = add_query_arg( array('unattached' => $unattached, 'ids' => join(',', $post_ids) ), $sendback );
                    break;

                default: return;
            }

            $sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );

            wp_redirect($sendback);
            exit();
        }
    }
}