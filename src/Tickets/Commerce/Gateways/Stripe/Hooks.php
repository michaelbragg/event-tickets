<?php

namespace TEC\Tickets\Commerce\Gateways\Stripe;

use TEC\Tickets\Commerce\Module;
use TEC\Tickets\Commerce\Notice_Handler;
use TEC\Tickets\Commerce\Payments_Tab;

/**
 * Class Hooks
 *
 * @since   TBD
 *
 * @package TEC\Tickets\Commerce\Gateways\Stripe
 */
class Hooks extends \tad_DI52_ServiceProvider {

	/**
	 * @inheritDoc
	 */
	public function register() {
		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Adds the actions required by each Stripe component.
	 *
	 * @since TBD
	 */
	protected function add_actions() {
		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
		add_action( 'wp', [ $this, 'maybe_create_stripe_payment_intent' ] );

		add_action( 'admin_init', [ $this, 'handle_stripe_errors' ] );
	}

	/**
	 * Adds the filters required by each Stripe component.
	 *
	 * @since TBD
	 */
	protected function add_filters() {
		add_filter( 'tec_tickets_commerce_gateways', [ $this, 'filter_add_gateway' ], 5, 2 );
		add_filter( 'tec_tickets_commerce_notice_messages', [ $this, 'include_admin_notices' ] );
		add_filter( 'tribe_settings_save_field_value', [ $this, 'validate_settings' ], 10, 3 );
	}

	/**
	 * Add this gateway to the list of available.
	 *
	 * @since TBD
	 *
	 * @param array $gateways List of available gateways.
	 *
	 * @return array
	 */
	public function filter_add_gateway( array $gateways = [] ) {
		return $this->container->make( Gateway::class )->register_gateway( $gateways );
	}

	/**
	 * Register the Endpoints from Stripe.
	 *
	 * @since TBD
	 */
	public function register_endpoints() {
		$this->container->make( REST::class )->register_endpoints();
	}

	/**
	 * Handle stripe errors into the admin UI.
	 *
	 * @since TBD
	 */
	public function handle_stripe_errors() {

		if ( empty( tribe_get_request_var( 'tc-stripe-error' ) ) ) {
			return;
		}

		tribe( Notice_Handler::class )->trigger_admin( tribe_get_request_var( 'tc-stripe-error' ) );
	}

	/**
	 * Include Stripe admin notices for Ticket Commerce.
	 *
	 * @since TBD
	 *
	 * @param array $messages Array of messages.
	 *
	 * @return array
	 */
	public function include_admin_notices( $messages ) {
		return array_merge( $messages, $this->container->make( Gateway::class )->get_admin_notices() );
	}

	/**
	 * Checks if Stripe is active and can be used to check out in the current cart and, if so,
	 * generates a payment intent
	 *
	 * @since TBD
	 */
	public function maybe_create_stripe_payment_intent() {

		if ( ! tribe( Merchant::class )->is_connected() || ! tribe( Module::class )->is_checkout_page() ) {
			return;
		}

		tribe( Client::class )->create_payment_intent();
	}

	public function validate_settings( $value, $field_id, $validated_field ) {

		if ( $field_id !== Settings::$option_checkout_element_payment_methods ) {
			return $value;
		}

		if ( ! tribe( Merchant::class )->is_connected() ) {
			return $value;
		}

		if ( ! isset( $_POST['tribeSaveSettings'] ) || ! isset( $_POST['current-settings-tab'] ) ) {
			return $value;
		}

		$payment_methods     = tribe_get_request_var( $field_id );
		$payment_intent_test = tribe( Payment_Intent::class )->test_payment_intent_creation( $payment_methods );

		if ( ! is_wp_error( $payment_intent_test ) ) {
			// Payment Settings are working, great!
			return $value;
		}

		// Provide a notice in the Dashboard
		\Tribe__Settings::instance()->errors[] = $payment_intent_test->get_error_message();

		// Revert value to the previous configuration
		return tribe_get_option( $field_id );
	}
}