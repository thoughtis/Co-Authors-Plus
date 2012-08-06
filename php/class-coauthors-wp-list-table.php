<?php
//Our class extends the WP_List_Table class, so we need to make sure that it's there

require_once( ABSPATH . 'wp-admin/includes/screen.php' );
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

/**
 * List all of the available Co-Authors within the system
 */
class CoAuthors_WP_List_Table extends WP_List_Table {

	var $is_search = false;

	function __construct() {
		if( !empty( $_REQUEST['s'] ) )
			$this->is_search = true;

		parent::__construct( array(
				'plural' => __( 'Co-Authors', 'co-authors-plus' ),
				'singular' => __( 'Co-Author', 'co-authors-plus' ),
			) );
	}

	/**
	 * Perform Co-Authors Query
	 */
	function prepare_items() {
		global $coauthors_plus;

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$paged = ( isset( $_REQUEST['paged'] ) ) ? intval( $_REQUEST['paged'] ) : 1;
		$per_page = 20;

		$args = array(
				'paged'          => $paged,
				'posts_per_page' => $per_page,
				'post_type'      => $coauthors_plus->guest_authors->post_type,
				'post_status'    => 'any',
				'orderby'        => 'post_title',
				'order'          => 'ASC',
			);

		$this->filters = array(
				'show-all'                => __( 'Show all', 'co-authors-plus' ),
				'with-linked-account'     => __( 'With linked account', 'co-authors-plus' ),
				'without-linked-account'  => __( 'Without linked account', 'co-authors-plus' ),
			);

		if ( isset( $_REQUEST['filter'] ) && array_key_exists( $_REQUEST['filter'], $this->filters ) ) {
			$this->active_filter = sanitize_key( $_REQUEST['filter'] );
		} else {
			$this->active_filter = 'show-all';
		}

		switch( $this->active_filter ) {
			case 'with-linked-account':
			case 'without-linked-account':
				$args['meta_key'] = $coauthors_plus->guest_authors->get_post_meta_key( 'linked_account' );
				if ( 'with-linked-account' == $this->active_filter )
					$args['meta_compare'] = '!=';
				else
					$args['meta_compare'] = '=';
				$args['meta_value'] = '0';
				break;
		}

		if( $this->is_search )
			add_filter( 'posts_where', array( $this, 'filter_query_for_search' ) );

		$author_posts = new WP_Query( $args );
		$items = array();
		foreach( $author_posts->get_posts() as $author_post ) {
			$items[] = $coauthors_plus->guest_authors->get_guest_author_by( 'id', $author_post->ID );
		}

		if( $this->is_search )
			remove_filter( 'posts_where', array( $this, 'filter_query_for_search' ) );

		$this->items = $items;

		$this->set_pagination_args( array(
			'total_items' => $author_posts->found_posts,
			'per_page' => $per_page,
			) );
	}

	function filter_query_for_search( $where ) {
		global $wpdb;
		$var = '%' . sanitize_text_field( $_REQUEST['s'] ) . '%';
		$where .= $wpdb->prepare( ' AND (post_title LIKE %s OR post_name LIKE %s )', $var, $var);
		return $where;
	}

	/**
	 * Either there are no guest authors, or the search doesn't match any
	 */
	function no_items() {
		_e( 'No matching guest authors were found.', 'co-authors-plus' );
	}

	/**
	 * Generate the columns of information to be displayed on our list table
	 *
	 * @todo display the post count
	 */
	function get_columns() {
		$columns = array(
				'display_name'   => __( 'Display Name', 'co-authors-plus' ),
				'first_name'     => __( 'First Name', 'co-authors-plus' ),
				'last_name'      => __( 'Last Name', 'co-authors-plus' ),
				'user_email'     => __( 'E-mail', 'co-authors-plus' ),
				'linked_account' => __( 'Linked Account', 'co-authors-plus' ),
			);
		return $columns;
	}

	/**
	 * Render a single row
	 */
	function single_row( $item ) {
		static $alternate_class = '';
		$alternate_class = ( $alternate_class == '' ? ' alternate' : '' );
		$row_class = ' class="guest-author-static' . $alternate_class . '"';

		echo '<tr id="guest-author-' . $item->ID . '"' . $row_class . '>';
		echo $this->single_row_columns( $item );
		echo '</tr>';
	}

	/**
	 * Render columns, some are overridden below
	 */
	function column_default( $item, $column_name ) {

		switch( $column_name ) {
			case 'first_name':
			case 'last_name':
				return $item->$column_name;
			case 'user_email':
				return '<a href="' . esc_attr( 'mailto:' . $item->user_email ) . '">' . esc_html( $item->user_email ) . '</a>';
		}
	}

	/**
	 * Render display name, e.g. author name
	 */
	function column_display_name( $item ) {

		$item_edit_link = get_edit_post_link( $item->ID );
		$item_view_link = get_author_posts_url( $item->ID, $item->user_nicename );

		$output = get_avatar( $item->user_email, 32 );
		// @todo caps check to see whether the user can edit. Otherwise, just show the name
		$output .= '<a href="' . esc_url( $item_edit_link ) . '">' . esc_html( $item->display_name ) . '</a>';

		$actions = array();
		$actions['edit'] = '<a href="' . esc_url( $item_edit_link ) . '">' . __( 'Edit', 'co-authors-plus' ) . '</a>';
		$actions['delete'] = '<a href="#">' . __( 'Delete', 'co-authors-plus' ) . '</a>';
		$actions['view'] = '<a href="' . esc_url( $item_view_link ) . '">' . __( 'View Posts', 'co-authors-plus' ) . '</a>';
		$actions = apply_filters( 'coauthors_guest_author_row_actions', $actions, $item );
		$output .= $this->row_actions( $actions, false );

		return $output;
	}

	/**
	 * Render linked account
	 */
	function column_linked_account( $item ) {
		if ( $item->linked_account ) {
			$account = get_user_by( 'login', $item->linked_account );
			if ( $account ) {
				if ( current_user_can( 'edit_users' ) ) {
					return '<a href="' . admin_url( 'user-edit.php?user_id=' . $account->ID ) . '">' . esc_html( $item->linked_account ) . '</a>';
				}
				return $item->linked_account;
			}
		}
		return '';
	}

	/**
	 * Allow users to filter the guest authors by various criteria
	 */
	function extra_tablenav( $which ) {

		?><div class="alignleft actions"><?php
		if ( 'top' == $which ) {
			if ( !empty( $this->filters ) ) {
				echo '<select name="filter">';
				foreach( $this->filters as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '" ' . selected( $this->active_filter, $key, false ) . '>' . esc_attr( $value ) . '</option>';
				}
				echo '</select>';
			}
			submit_button( __( 'Filter', 'co-authors-plus' ), 'secondary', false, false );
		}
		?></div><?php
	}

	function display() {
		global $coauthors_plus;
		$this->search_box( $coauthors_plus->guest_authors->labels['search_items'], 'guest-authors' );
		parent::display();
	}

}