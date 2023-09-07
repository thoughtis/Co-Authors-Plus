<?php
/**
 * Blocks
 * 
 * @package CoAuthors
 */

namespace CoAuthors;

use WP_REST_Request;

/**
 * Blocks
 */
class Blocks {
	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'initialize_blocks' ) );
	}

	/**
	 * Initialize Blocks
	 */
	public static function initialize_blocks() : void {

		if ( ! apply_filters( 'coauthors_plus_support_blocks', true ) ) {
			return;
		}

		add_action( 'render_block_context', array( __CLASS__, 'provide_author_archive_context' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_store' ) );

		/**
		 * Templating functions used by many block render functions.
		 */
		require_once __DIR__ . '/templating/class-templating.php';

		/**
		 * Individual blocks.
		 */
		require_once __DIR__ . '/block-coauthors/class-block-coauthors.php';
		Blocks\Block_CoAuthors::register_block();

		require_once __DIR__ . '/block-coauthor-avatar/class-block-coauthor-avatar.php';
		Blocks\Block_CoAuthor_Avatar::register_block();

		require_once __DIR__ . '/block-coauthor-description/class-block-coauthor-description.php';
		Blocks\Block_CoAuthor_Description::register_block();

		require_once __DIR__ . '/block-coauthor-name/class-block-coauthor-name.php';
		Blocks\Block_CoAuthor_Name::register_block();

		require_once __DIR__ . '/block-coauthor-featured-image/class-block-coauthor-featured-image.php';
		Blocks\Block_CoAuthor_Featured_Image::register_block();
	}

	/**
	 * Provide Author Archive Context
	 *
	 * @param array $context, 
	 * @param array $parsed_block
	 * @return array
	 */
	public static function provide_author_archive_context( array $context, array $parsed_block ) : array {
		if ( ! is_author() ) {
			return $context;
		}

		if ( null === $parsed_block['blockName'] ) {
			return $context;
		}

		$uses_author_context = apply_filters(
			'coauthors_blocks_block_uses_author_context',
			'cap/coauthor-' === substr( $parsed_block['blockName'], 0, 13  ),
			$parsed_block['blockName']
		);
		
		$has_author_context = array_key_exists( 'cap/author', $context ) && is_array( $context['cap/author'] );

		if ( ! $uses_author_context || $has_author_context ) {
			return $context;
		}

		$author = rest_get_server()->dispatch(
			WP_REST_Request::from_url(
				home_url(
					sprintf(
						'/wp-json/coauthors-blocks/v1/coauthor/%s',
						get_query_var( 'author_name' )
					)
				)
			)
		)->get_data();

		if ( ! is_array( $author ) || ! array_key_exists( 'id', $author ) ) {
			return $context;
		}

		return array(
			'cap/author' => $author
		);
	}

	/**
	 * Enqueue Store
	 */
	public static function enqueue_store() : void {
		$asset = require realpath( __DIR__ . '/../..' ) . '/build/blocks-store/index.asset.php';

		wp_enqueue_script(
			'coauthors-blocks-store',
			plugins_url( '/co-authors-plus/build/blocks-store/index.js' ),
			$asset['dependencies'],
			$asset['version']
		);

		$data = apply_filters(
			'coauthors_blocks_store_data',
			array(
				'authorPlaceholder' => array(
					'id'             => 0,
					'display_name'   => 'FirstName LastName',
					'description'    => array(
						'raw'      => 'Placeholder description from Co-Authors block.',
						'rendered' => '<p>Placeholder description from Co-Authors block.</p>'
					),
					'link'           => '#',
					'featured_media' => 0,
					'avatar_urls'    => array_map( '__return_empty_string', array_flip( rest_get_avatar_sizes() ) )
				)
			)
		);

		wp_localize_script(
			'coauthors-blocks-store',
			'coAuthorsBlocks',
			$data
		);
	}
}