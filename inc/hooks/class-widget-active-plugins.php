<?php
/**
 * Active Plugins Multisite Dashboard Widget
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Active_Plugins_Dashboard_Widget {
	
	public function __construct() {
        add_action(
                'admin_head-index.php', array( $this, 'print_scripts' )
        );
        add_action(
                'wp_network_dashboard_setup', array( $this, 'network_dashboard_setup' )
        );
	}


	/**
	 * Active plugins widget scripts
	 * @global type $current_screen
	 * @return type
	 */
	public function print_scripts() {
		global $current_screen;
		if( !$current_screen->is_network )
			return;

        echo '<style type="text/css">
		#active_network_plugins .inside, 
		#all_network_plugins .inside {
			margin:0;padding:0
		} 
		.mtt-dash-widget tbody tr:hover {
			background-color: #FFFACD
		} 
		.alternate{
			font-weight:bold
		}
		#active_network_plugins .dashboard-widget-control-form,
		#all_network_plugins .dashboard-widget-control-form {
			padding: 5px 0 20px 20px;
		}
		.row-title.single-site { background-color:#EBEBEB}';
        if( defined( 'MP6' ) ) {
            echo '
			.mtt-dash-widget td {
				line-height:0.8em
			} 
			';
        }
        echo '</style>';
	}


	/**
	 * Active plugins widget add
	 */
	public function network_dashboard_setup() {
		wp_add_dashboard_widget(
				'active_network_plugins', 
				'Network active plugins', 
				array( $this, 'active_network_plugins' )
		);
		wp_add_dashboard_widget(
				'all_network_plugins', 
				'Sites active theme and plugins', 
				array( $this, 'all_network_plugins' )
		);
	}


	/**
	 *  Network active plugins widget render
	 */
	public function active_network_plugins() {

		$the_plugs = get_site_option( 'active_sitewide_plugins' );
        echo '<table class="widefat mtt-dash-widget"><tbody>';
        $this->print_plugins_list( $the_plugs, 'network' );
        printf(
            '</tbody>
				<tfoot><tr>
					<th class="row-title"><em style="opacity:.5">%s: %s</em></th>
					<!--<th></th>-->
				</tr></tfoot></table>', __( 'Total', 'mtt' ), count( $the_plugs )
        );
        //echo '</tbody></table>';
	}


	/**
	 *  All sites active plugins widget render
	 */
	public function all_network_plugins() {
		global $wpdb;
		$blogs = $wpdb->get_results( "
                SELECT blog_id
                FROM {$wpdb->blogs}
                WHERE site_id = '{$wpdb->siteid}'
                AND spam = '0'
                AND deleted = '0'
                AND archived = '0'
            " );

		foreach( $blogs as $blog ) {
			$the_plugs = get_blog_option( $blog->blog_id, 'active_plugins' );
			if( count( $the_plugs ) == 0 )
				continue;
			$the_theme = B5F_MTT_Utils::get_blog_theme( $blog->blog_id, false );
			$blogname = get_blog_option( $blog->blog_id, 'blogname' );
			$this->print_table( '<strong>' . $blogname . '</strong> using theme ' . $the_theme );
			$this->print_plugins_list( $the_plugs, 'site' );
			printf(
	            '</tbody>
					<tfoot><tr>
						<th class="row-title"><em style="opacity:.5">%s: %s</em></th>
						<!--<th></th>-->
					</tr></tfoot></table><br class="clear">', __( 'Total', 'mtt' ), count( $the_plugs )
	        );
		}
	}

    
    /**
     * Prints the start of the table
     * 
     * @param string $title
     * 
     * @since 2.3.7
     * @access private
     */
    private function print_table( $title = '' ) {
        ?>
        <table class="widefat mtt-dash-widget"><tbody>
            <thead>
                <tr>
                    <th class="row-title single-site"><em><?php echo $title; ?></em></th>
                </tr>
            </thead>
            <tbody>
        <?php
    }

    /**
     * Prints the list of plugins
     * 
     * @param array     $plugins    List of folders inside a directory
     * @param string    $type		Different objects received for Network or Single
     * 
     * @since 2.3.7
     * @access private
     */
    private function print_plugins_list( $plugins, $type ) {
        $count = 0;
		if( 'network' == $type ) {
	        foreach( $plugins as $plug => $time ) {
		        $alt = (++$count % 2 ) ? 'alternate' : '';
				$this->print_plugin_row( $plug, $alt );
	        }
		}
		else {
	        foreach( $plugins as $plug ) {
		        $alt = (++$count % 2 ) ? 'alternate' : '';
				$this->print_plugin_row( $plug, $alt );
	        }
		}
    }


    /**
     * Helper function for the previous
     * 
     * @param array     $plugin    	Plugin details
     * @param string   	$alt		Alternate rows
     * 
     * @since 2.3.7
     * @access private
     */
	private function print_plugin_row( $plugin, $alt ) {
        $name = explode( '/', $plugin ); // Folder name will be displayed
        printf(
            '<tr class="%s">
                <td class="row"><tt>%s</tt></td>
                <!--<td><tt></tt></td>-->
            </tr>', $alt, $name[0]
        );
	}

}