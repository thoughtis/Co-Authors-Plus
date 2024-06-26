<?php
/**
 * For themes where it's easily doable, add support for Co-Authors Plus on the frontend
 * by filtering the common template tags
 */

class CoAuthors_Template_Filters {

	public function __construct() {
		add_filter( 'the_author', array( $this, 'filter_the_author' ) );
		add_filter( 'the_author_posts_link', array( $this, 'filter_the_author_posts_link' ) );

		// Add support for Guest Authors in RSS feeds.
		add_filter( 'the_author', array( $this, 'filter_the_author_rss' ), 15 ); // Override CoAuthors_Template_Filters::filter_the_author for RSS feeds
		add_action( 'rss2_item', array( $this, 'action_add_rss_guest_authors' ) );
	}

	public function filter_the_author(): string {
		return coauthors( null, null, null, null, false );
	}

	public function filter_the_author_posts_link(): string {
		return coauthors_posts_links( null, null, null, null, false );
	}

	public function filter_the_author_rss( $the_author ) {
		if ( ! function_exists( 'coauthors' ) || ! is_feed() ) {
			return $the_author;
		}

		$coauthors = (array) get_coauthors();
		if ( count( $coauthors ) >= 1 && isset( $coauthors[0]->display_name ) ) {
			return $coauthors[0]->display_name;
		}

		return $the_author;
	}

	public function action_add_rss_guest_authors(): void {
		$coauthors = get_coauthors();

		// remove the first guest author who is added to the first dc:creator element
		array_shift( $coauthors );

		foreach ( $coauthors as $coauthor ) {
			echo '      <dc:creator><![CDATA[' . esc_html( $coauthor->display_name ) . "]]></dc:creator>\n";
		}
	}
}
