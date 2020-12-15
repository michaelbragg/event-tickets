<?php

use Tribe__Utils__Array as Arr;

/**
 * The ORM/Repository class for RSVP attendees.
 *
 * @since 4.10.6
 *
 * @property Tribe__Tickets__RSVP $attendee_provider
 */
class Tribe__Tickets__Repositories__Attendee__RSVP extends Tribe__Tickets__Attendee_Repository {

	/**
	 * Key name to use when limiting lists of keys.
	 *
	 * @var string
	 */
	protected $key_name = 'rsvp';

	/**
	 * {@inheritdoc}
	 */
	public function __construct() {
		parent::__construct();

		$this->attendee_provider = tribe( 'tickets.rsvp' );

		$this->create_args['post_type'] = $this->attendee_provider->attendee_object;

		// Use a regular variable so we can get constants from it PHP <7.0
		$attendee_provider = $this->attendee_provider;

		// Add object specific aliases.
		$this->update_fields_aliases = array_merge( $this->update_fields_aliases, [
			'ticket_id'       => $attendee_provider::ATTENDEE_PRODUCT_KEY,
			'event_id'        => $attendee_provider::ATTENDEE_EVENT_KEY,
			'post_id'         => $attendee_provider::ATTENDEE_EVENT_KEY,
			'security_code'   => $attendee_provider->security_code,
			'order_id'        => $attendee_provider->order_key,
			'optout'          => $attendee_provider::ATTENDEE_OPTOUT_KEY,
			'user_id'         => $attendee_provider->attendee_user_id,
			'price_paid'      => '_paid_price',
			'full_name'       => $attendee_provider->full_name,
			'email'           => $attendee_provider->email,
			'attendee_status' => $attendee_provider::ATTENDEE_RSVP_KEY,
		] );

		add_filter( 'tribe_tickets_attendee_repository_set_attendee_args_' . $this->key_name, [ $this, 'filter_set_attendee_args' ], 10, 3 );
	}

	/**
	 * {@inheritdoc}
	 */
	public function attendee_types() {
		return $this->limit_list( $this->key_name, parent::attendee_types() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function attendee_to_event_keys() {
		return $this->limit_list( $this->key_name, parent::attendee_to_event_keys() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function attendee_to_ticket_keys() {
		return $this->limit_list( $this->key_name, parent::attendee_to_ticket_keys() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function attendee_to_order_keys() {
		return $this->limit_list( $this->key_name, parent::attendee_to_order_keys() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function purchaser_name_keys() {
		return $this->limit_list( $this->key_name, parent::purchaser_name_keys() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function purchaser_email_keys() {
		return $this->limit_list( $this->key_name, parent::purchaser_email_keys() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function security_code_keys() {
		return $this->limit_list( $this->key_name, parent::security_code_keys() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function attendee_optout_keys() {
		return $this->limit_list( $this->key_name, parent::attendee_optout_keys() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function checked_in_keys() {
		return $this->limit_list( $this->key_name, parent::checked_in_keys() );
	}

	/**
	 * Filter the arguments to set for the attendee for this provider.
	 *
	 * @since TBD
	 *
	 * @param array                         $args          List of arguments to set for the attendee.
	 * @param array                         $attendee_data List of additional attendee data.
	 * @param Tribe__Tickets__Ticket_Object $ticket        The ticket object or null if not relying on it.
	 *
	 * @return array List of arguments to set for the attendee.
	 */
	public function filter_set_attendee_args( $args, $attendee_data, $ticket = null ) {
		// Set default order ID.
		if ( empty( $args['order_id'] ) ) {
			// Use a regular variable so we can call a static method from it PHP <7.0
			$attendee_provider = $this->attendee_provider;

			$args['order_id'] = $attendee_provider::generate_order_id();
		}

		// Set default attendee status.
		if ( ! isset( $args['attendee_status'] ) ) {
			$args['attendee_status'] = 'yes';
		}

		return $args;
	}

	/**
	 * Handle backwards compatible actions for RSVPs.
	 *
	 * @since TBD
	 *
	 * @param WP_Post                       $attendee      The attendee object.
	 * @param array                         $attendee_data List of additional attendee data.
	 * @param Tribe__Tickets__Ticket_Object $ticket        The ticket object.
	 */
	public function trigger_create_actions( $attendee, $attendee_data, $ticket ) {
		parent::trigger_create_actions( $attendee, $attendee_data, $ticket );

		$attendee_id       = $attendee->ID;
		$post_id           = Arr::get( $attendee_data, 'post_id' );
		$order_id          = $attendee_data['order_id'];
		$product_id        = $ticket->ID;
		$order_attendee_id = Arr::get( $attendee_data, 'order_attendee_id' );

		/**
		 * RSVP specific action fired when a RSVP-driven attendee ticket for an event is generated.
		 * Used to assign a unique ID to the attendee.
		 *
		 * @param int    $attendee_id ID of attendee ticket.
		 * @param int    $post_id     ID of event.
		 * @param string $order_id    RSVP order ID (hash).
		 * @param int    $product_id  RSVP product ID.
		 */
		do_action( 'event_tickets_rsvp_attendee_created', $attendee_id, $post_id, $order_id, $product_id );

		/**
		 * Action fired when an RSVP attendee ticket is created.
		 * Used to store attendee meta.
		 *
		 * @param int $attendee_id       ID of the attendee post.
		 * @param int $post_id           Event post ID.
		 * @param int $product_id        RSVP ticket post ID.
		 * @param int $order_attendee_id Attendee # for order.
		 */
		do_action( 'event_tickets_rsvp_ticket_created', $attendee_id, $post_id, $product_id, $order_attendee_id );

		if ( null === $order_attendee_id ) {
			/**
			 * Action fired when an RSVP ticket has had attendee tickets generated for it.
			 *
			 * @param int    $product_id RSVP ticket post ID.
			 * @param string $order_id   ID (hash) of the RSVP order.
			 * @param int    $qty        Quantity ordered.
			 */
			do_action( 'event_tickets_rsvp_tickets_generated_for_product', $product_id, $order_id, 1 );
		}
	}
}
