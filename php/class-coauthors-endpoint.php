<?php

require_once ABSPATH . 'wp-includes/rest-api/class-wp-rest-response.php';
require_once ABSPATH . 'wp-includes/rest-api/class-wp-rest-request.php';

/**
 * Class Endpoint.
 */
class CoAuthors_Endpoint {

	/**
	 * Namespace for our endpoints.
	 */
	protected const NAMESPACE = 'coauthors/v1';

	/**
	 * Route for authors search endpoint.
	 */
	protected const ROUTE = 'search';

	/**
	 * Regex to capture the query in a request.
	 */
	// https://regex101.com/r/3HaxlL/1
	protected const ENDPOINT_QUERY_REGEX = '/(?P<q>[\w]+)';

	/**
	 * An instance of the Co_Authors_Plus class.
	 */
	private $coauthors;

	/**
	 * WP_REST_API constructor.
	 */
	public function __construct( $coauthors_instance ) {
		$this->coauthors = $coauthors_instance;

		add_action( 'rest_api_init', [ $this, 'add_endpoints' ] );
	}

	/**
	 * Register endpoints.
	 */
	public function add_endpoints(): void {
		register_rest_route(
			static::NAMESPACE,
			static::ROUTE . static::ENDPOINT_QUERY_REGEX,
			[
				[
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_coauthors' ],
					'permission_callback' => '__return_true',
					'args'                => [
						'q' => [
							'required'          => true,
							'type'              => 'string',
						],
					],
				]
			]
		);
	}

	public function get_coauthors( WP_REST_Request $request ): WP_REST_Response {
		$search   = sanitize_text_field( strtolower( $request['q'] ) );
		$ignore   = array_map( 'sanitize_text_field', explode( ',', $request['existing_authors'] ) );
		$response = $this->coauthors->search_authors( $search, $ignore );

		// Return message if no authors found
		if ( empty( $response ) ) {
			$response = apply_filters( 'coauthors_no_matching_authors_message', 'Sorry, no matching authors found.' );
		}

		return rest_ensure_response( $response );
	}
}
