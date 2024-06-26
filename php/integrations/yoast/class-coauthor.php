<?php
/**
 * The Co-Author Schema class used by Yoast integration
 */

namespace CoAuthors\Integrations\Yoast;

use Yoast\WP\SEO\Config\Schema_IDs;
use Yoast\WP\SEO\Generators\Schema\Author;

/**
 * Returns schema Author data for the CoAuthor Plus assigned user on a post.
 */
class CoAuthor extends Author {

	/**
	 * The user ID of the author we're generating data for.
	 *
	 * @var int $user_id
	 */
	private $user_id;

	/**
	 * Determine whether we should return Person schema.
	 *
	 * @return bool
	 */
	public function is_needed(): bool {
		return true;
	}

	/**
	 * Returns Person Schema data.
	 *
	 * @return bool|array Person data on success, false on failure.
	 */
	public function generate() {
		$user_id = $this->determine_user_id();
		if ( ! $user_id ) {
			return false;
		}

		$data = $this->build_person_data( $user_id, true );

		$data['@type'] = 'Person';
		unset( $data['logo'] );

		// If this is a post and the author archives are enabled, set the author archive url as the author url.
		if ( $this->helpers->options->get( 'disable-author' ) !== true ) {
			$data['url'] = $this->helpers->user->get_the_author_posts_url( $user_id );
		}

		return $data;
	}

	/**
	 * Generate the Person data given a user ID.
	 *
	 * @param int $user_id User ID.
	 *
	 * @return array|bool
	 */
	public function generate_from_user_id( $user_id ) {
		$this->user_id = $user_id;

		return $this->generate();
	}

	/**
	 * Generate the Person data given a Guest Author object.
	 *
	 * @param object $guest_author The Guest Author object.
	 *
	 * @return array|bool
	 */
	public function generate_from_guest_author( $guest_author ) {
		$data = $this->build_person_data_for_guest_author( $guest_author, true );

		$data['@type'] = 'Person';
		unset( $data['logo'] );

		// If this is a post and the author archives are enabled, set the author archive url as the author url.
		if ( $this->helpers->options->get( 'disable-author' ) !== true ) {
			$data['url'] = \get_author_posts_url( $guest_author->ID, $guest_author->user_nicename );
		}

		return $data;
	}

	/**
	 * Determines a User ID for the Person data.
	 *
	 * @return bool|int User ID or false upon return.
	 */
	protected function determine_user_id() {
		return $this->user_id;
	}

	/**
	 * Builds our array of Schema Person data for a given Guest Author.
	 *
	 * @param object $guest_author The Guest Author object.
	 * @param bool   $add_hash Whether the person's image url hash should be added to the image id.
	 *
	 * @return array An array of Schema Person data.
	 */
	protected function build_person_data_for_guest_author( $guest_author, $add_hash = false ): array {
		$schema_id = $this->context->site_url . Schema_IDs::PERSON_LOGO_HASH;
		$data      = [
			'@type' => $this->type,
			'@id'   => $schema_id . \wp_hash( $guest_author->user_login . $guest_author->ID . 'guest' ),
		];

		$data['name'] = $this->helpers->schema->html->smart_strip_tags( $guest_author->display_name );

		$data = $this->set_image_from_avatar( $data, $guest_author, $schema_id, $add_hash );

		// If local avatar is present, override.
		$avatar_meta = \wp_get_attachment_image_src( \get_post_thumbnail_id( $guest_author->ID ) );
		if ( $avatar_meta ) {
			$avatar_meta   = [
				'url'    => $avatar_meta[0],
				'width'  => $avatar_meta[1],
				'height' => $avatar_meta[2],
			];
			$data['image'] = $this->helpers->schema->image->generate_from_attachment_meta( $schema_id, $avatar_meta, $data['name'], $add_hash );
		}

		if ( ! empty( $guest_author->description ) ) {
			$data['description'] = $this->helpers->schema->html->smart_strip_tags( $guest_author->description );
		}

		$data = $this->add_guest_author_same_as_urls( $data, $guest_author );

		return $data;
	}

	/**
	 * Builds our SameAs array.
	 *
	 * @param array   $data         The Person schema data.
	 * @param WP_User $guest_author The user data object.
	 *
	 * @return array The Person schema data.
	 */
	protected function add_guest_author_same_as_urls( $data, $guest_author ): array {
		$same_as_urls = [];

		// Add the "Website" field from co-authors' contact info.
		if ( ! empty( $guest_author->website ) ) {
			$same_as_urls[] = $guest_author->website;
		}

		// When CAP adds it, add the social profiles here.

		if ( ! empty( $same_as_urls ) ) {
			$same_as_urls   = \array_values( \array_unique( $same_as_urls ) );
			$data['sameAs'] = $same_as_urls;
		}

		return $data;
	}
}
