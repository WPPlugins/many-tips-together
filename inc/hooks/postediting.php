<?php
/**
 * Post Editing hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_Post_Editing
{
	// store the options
	protected $params;

	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct( $options ) {

		$this->params = $options;

		# MOVED BOTH TO TOP PLUGIN ACTION
		// REVISIONS
		// AUTOSAVE
		
		// PAGE EXCERPTS
		if( !empty( $options['postpages_enable_page_excerpts'] ) )
			add_action(
					'init', array( $this, 'page_excerpts' )
			);

		// COUNT CATEGORIES
		if( !empty( $options['postpages_enable_category_count'] ) )
		{
			add_action( 'load-post-new.php', array( $this, 'category_count_load' ) );
			add_action( 'load-post.php', array( $this, 'category_count_load' ) );
		}
		
		// CATEGORIES CHECK LIST
		if( !empty( $options['postpages_enable_category_fixed'] ) )
		{
			include_once 'class-category-check-list-tree.php';
		}
		
		// NO PARENT CATEGORIES
		if( !empty( $options['postpages_enable_category_noparent'] ) )
		{
			add_action( 'admin_footer-post.php', array( $this, 'category_noparent' ) );
			add_action( 'admin_footer-post-new.php', array( $this, 'category_noparent' ) );
		}
		
		// HIDE ELEMENTS IN PUBLISH META BOX
		if( !empty( $options['postpages_hide_from_publish'] ) )
		{
			add_action(
					'admin_head-post.php', array( $this, 'hide_from_publish' )
			);
			add_action(
					'admin_head-post-new.php', array( $this, 'hide_from_publish' )
			);
		}

		// MOVE AUTHOR META BOX
		if( !empty( $options['postpages_move_author_metabox'] ) )
		{
			add_action(
					'admin_menu', array( $this, 'autor_metabox_remove' )
			);
			add_action(
					'post_submitbox_misc_actions', array( $this, 'autor_metabox_move' )
			);
		}

		// MOVE COMMENTS META BOX
		if( !empty( $options['postpages_move_comments_metabox'] ) )
		{
			add_action(
					'add_meta_boxes', array( $this, 'comments_metabox_remove' )
			);
			add_action(
					'post_submitbox_misc_actions', array( $this, 'comments_metabox_move' )
			);
		}

		// META BOXES REMOVAL
		add_action(
				'add_meta_boxes', array( $this, 'all_metabox_remove' )
		);
	}


	/**
	 * Category Count load
	 */
	public function category_count_load()
	{
		global $typenow;
		if( !in_array( $typenow, apply_filters( 'mtt_category_counts_cpts', array( 'post' ) ) ) )
			return;

		add_filter( 'the_category', array( $this, 'category_count_do' ) );
	}
	
	/**
	 * TODO: docs
	 * @param type $cat_name
	 * @return type
	 */
	public function category_count_do( $cat_name )
	{
		$cat_id = get_cat_ID( $cat_name );
		$category = get_category( $cat_id );
		$count = $category->category_count;
		return "$cat_name ($count)";
	}
	
	/**
	 * TODO: docs
	 * @global type $typenow
	 * @return type
	 */
	public function category_noparent()
	{
		global $typenow;

		if ( 'post' != $typenow )
			return;
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {   
			$("#categorychecklist>li>label").each(function(){
				if( $(this).parent().children('ul').length > 0)
					$(this).find('input').hide();
			});
		});
		</script>
		<?php
	}
	
	
	/**
	 * Remove Author meta box
	 * 
	 */
	public function autor_metabox_remove()
	{
		remove_meta_box( 'authordiv', 'post', 'normal' );
		remove_meta_box( 'authordiv', 'page', 'normal' );
	}


	/**
	 * Move Author meta box into Publish
	 * 
	 * @global type $post_ID
	 */
	public function autor_metabox_move()
	{
		global $post;
		if( !post_type_supports( $post->post_type, 'author' ) )
			return;
		
		echo '<div id="author" class="misc-pub-section" style="border-top-style:solid; border-top-width:1px; border-top-color:#EEEEEE; border-bottom-width:0px;">Author: ';
		post_author_meta_box( $post );
		echo '</div>';
	}


	/**
	 * Remove Comments meta box
	 * 
	 */
	public function comments_metabox_remove()
	{
		remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
	}


	/**
	 * Hide elements from Publish meta box
	 * 
	 */
	public function hide_from_publish()
	{
		$style = '';
		if( in_array( 'status', $this->params['postpages_hide_from_publish'] ) )
			$style .= '.misc-pub-section:first-child {display:none}';

		if( in_array( 'visibility', $this->params['postpages_hide_from_publish'] ) )
			$style .= '#visibility.misc-pub-section {display:none}';

		if( in_array( 'published', $this->params['postpages_hide_from_publish'] ) )
			$style .= '.misc-pub-section.curtime {display:none}';

		if( in_array( 'revisions', $this->params['postpages_hide_from_publish'] ) )
			$style .= '#misc-publishing-actions .misc-pub-revisions {display:none}';

		if( '' != $style )
			echo '<style>' . $style . '</style>';
	}


	/**
	 * Move Comments meta box inside Publish
	 * @global type $post_ID
	 */
	public function comments_metabox_move()
	{
		global $post;
		if( !post_type_supports( $post->post_type, 'comments' ) )
			return;
		
		echo '<div id="commentstatusdiv" class="misc-pub-section" style="border-top-style:solid; border-top-width:1px; border-top-color:#EEEEEE; border-bottom-width:0px;margin-bottom: -24px"><div style="margin-bottom:-7px;font-weight: bold;">Comments:</div>';
		post_comment_status_meta_box( $post );
		echo '</div>';
	}


	/**
	 * Manage Meta Boxes removal
	 * @global type $current_screen
	 * @return type
	 */
	public function all_metabox_remove()
	{
		global $current_screen;
		if( 'attachment' == $current_screen->post_type )
			return;

		// AUTHOR
		if( !empty( $this->params['postpages_disable_mbox_author'] ) )
		{

			switch( $this->params['postpages_disable_mbox_author'] )
			{
				case 'none':
					break;
				case 'post':
					remove_meta_box( 'authordiv', 'post', 'normal' );
					break;
				case 'page':
					remove_meta_box( 'authordiv', 'page', 'normal' );
					break;
				default:
					remove_meta_box( 'authordiv', 'page', 'normal' );
					remove_meta_box( 'authordiv', 'post', 'normal' );
					break;
			}
		}

		// COMMENT STATUS
		if( !empty( $this->params['postpages_disable_mbox_comment_status'] ) )
		{
			switch( $this->params['postpages_disable_mbox_comment_status'] )
			{
				case 'none':
					break;
				case 'post':
					remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
					break;
				case 'page':
					remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
					break;
				default:
					remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
					remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
					break;
			}
		}

		// COMMENTS
		if( !empty( $this->params['postpages_disable_mbox_comment'] ) )
		{
			switch( $this->params['postpages_disable_mbox_comment'] )
			{
				case 'none':
					break;
				case 'post':
					remove_meta_box( 'commentsdiv', 'post', 'normal' );
					break;
				case 'page':
					remove_meta_box( 'commentsdiv', 'page', 'normal' );
					break;
				default:
					remove_meta_box( 'commentsdiv', 'page', 'normal' );
					remove_meta_box( 'commentsdiv', 'post', 'normal' );
					break;
			}
		}

		// CUSTOM FIELDS
		if( !empty( $this->params['postpages_disable_mbox_custom_fields'] ) )
		{
			switch( $this->params['postpages_disable_mbox_custom_fields'] )
			{
				case 'none':
					break;
				case 'post':
					remove_meta_box( 'postcustom', 'post', 'normal' );
					break;
				case 'page':
					remove_meta_box( 'postcustom', 'page', 'normal' );
					break;
				default:
					remove_meta_box( 'postcustom', 'page', 'normal' );
					remove_meta_box( 'postcustom', 'post', 'normal' );
					break;
			}
		}

		// FEATURED IMAGE
		if( !empty( $this->params['postpages_disable_mbox_featured_image'] ) )
		{
			switch( $this->params['postpages_disable_mbox_featured_image'] )
			{
				case 'none':
					break;
				case 'post':
					remove_meta_box( 'postimagediv', 'post', 'side' );
					break;
				case 'page':
					remove_meta_box( 'postimagediv', 'page', 'side' );
					break;
				default:
					remove_meta_box( 'postimagediv', 'page', 'side' );
					remove_meta_box( 'postimagediv', 'post', 'side' );
					break;
			}
		}

		// REVISIONS
		if( !empty( $this->params['postpages_disable_mbox_revisions'] ) )
		{
			switch( $this->params['postpages_disable_mbox_revisions'] )
			{
				case 'none':
					break;
				case 'post':
					remove_meta_box( 'revisionsdiv', 'post', 'normal' );
					break;
				case 'page':
					remove_meta_box( 'revisionsdiv', 'page', 'normal' );
					break;
				default:
					remove_meta_box( 'revisionsdiv', 'page', 'normal' );
					remove_meta_box( 'revisionsdiv', 'post', 'normal' );
					break;
			}
		}

		// SLUG
		if( !empty( $this->params['postpages_disable_mbox_slug'] ) )
		{
			switch( $this->params['postpages_disable_mbox_slug'] )
			{
				case 'none':
					break;
				case 'post':
					remove_meta_box( 'slugdiv', 'post', 'normal' );
					break;
				case 'page':
					remove_meta_box( 'slugdiv', 'page', 'normal' );
					break;
				default:
					remove_meta_box( 'slugdiv', 'page', 'normal' );
					remove_meta_box( 'slugdiv', 'post', 'normal' );
					break;
			}
		}

		// PAGE ATTRIBUTES
		if( !empty( $this->params['postpages_disable_mbox_attributes'] ) )
			remove_meta_box( 'pageparentdiv', 'page', 'side' );

		// CATEGORY
		if( !empty( $this->params['postpages_disable_mbox_format'] ) )
			remove_meta_box( 'formatdiv', 'post', 'side' );

		// CATEGORY
		if( !empty( $this->params['postpages_disable_mbox_category'] ) )
			remove_meta_box( 'categorydiv', 'post', 'side' );

		// EXCERPT
		if( !empty( $this->params['postpages_disable_mbox_excerpt'] ) )
			remove_meta_box( 'postexcerpt', 'post', 'normal' );

		// POST TAGS
		if( !empty( $this->params['postpages_disable_mbox_tags'] ) )
			remove_meta_box( 'tagsdiv-post_tag', 'post', 'side' );

		// TRACKBACKS
		if( !empty( $this->params['postpages_disable_mbox_trackbacks'] ) )
			remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
	}


	/**
	 * Enable Page excerpts
	 * 
	 * Via Smashing Magazine : http://goo.gl/cSCpy
	 */
	public function page_excerpts()
	{
		add_post_type_support( 'page', 'excerpt' );
	}

}