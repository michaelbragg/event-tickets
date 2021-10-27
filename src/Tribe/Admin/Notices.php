<?php

use \TEC\Tickets\Commerce;

/**
 * Class Tribe__Tickets__Admin__Notices
 *
 * @since 4.7
 */
class Tribe__Tickets__Admin__Notices {

	/**
	 * Hooks the actions and filters used by the class
	 *
	 * @since 4.7
	 */
	public function hook() {
		add_action( 'admin_init', [ $this, 'maybe_display_notices' ] );
	}

	/**
	 * Maybe display admin notices.
	 *
	 * @since 4.12.3
	 */
	public function maybe_display_notices() {
		// Bail on the unexpected
		if (
			! function_exists( 'tribe_installed_before' )
			|| ! class_exists( 'Tribe__Admin__Notices' )
		) {
			return;
		}

		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		$this->maybe_display_rsvp_new_views_options_notice();
		$this->maybe_display_classic_editor_ecp_recurring_tickets_notice();
		$this->maybe_display_plus_commerce_notice();
		$this->maybe_display_tickets_commerce_checkout_setting_notice();
		$this->maybe_display_tickets_commerce_success_setting_notice();
	}

	/**
	 * Display dismissible notice about new RSVP view settings.
	 *
	 * @since 4.12.3
	 */
	public function maybe_display_rsvp_new_views_options_notice() {
		// Bail if previously dismissed this notice.
		if ( Tribe__Admin__Notices::instance()->has_user_dimissed( __FUNCTION__ ) ) {
			return;
		}

		/** @var Tribe__Settings $settings */
		$settings = tribe( 'settings' );

		// Bail if user cannot change settings.
		if ( ! current_user_can( $settings->requiredCap ) ) {
			return;
		}

		// Only show to previously existing installs.
		if ( ! tribe_installed_before( 'Tribe__Tickets__Main', '5.0' ) ) {
			return;
		}

		// Bail if we aren't in Events > Settings.
		if ( 'tribe-common' !== tribe_get_request_var( 'page' ) ) {
			return;
		}

		// Bail if already at wp-admin > Events > Settings > Tickets tab to avoid redundancy/confusion by linking to itself.
		if ( 'display' === tribe_get_request_var( 'tab' ) ) {
			return;
		}

		// Bail if the option is already in use.
		if ( tribe_tickets_rsvp_new_views_is_enabled() ) {
			return;
		}

		// Get link to Display Tab.
		$url = $settings->get_url( [
			'page' => 'tribe-common',
			'tab'  => 'display',
		] );

		$link = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $url ),
			esc_html_x( 'RSVP Display Settings', 'Admin notice link text', 'event-tickets' )
		);

		// Set heading text.
		$heading = __( 'Event Tickets', 'event-tickets' );

		// Build notice text.
		$text = sprintf(
			// translators: %1$s: RSVP singular text, %2$s: Link to settings page.
			__( 'With this new version, we\'ve introduced newly redesigned %1$s frontend views. If you have customized the %1$s section, this update will likely impact your customizations.

			To upgrade to the new frontend views, please enable them in the %2$s.', 'event-tickets' ),
			tribe_get_rsvp_label_singular( 'admin_notices' ),
			$link
		);

		// Build notice message.
		$message = sprintf( '<h3>%1$s</h3>%2$s', $heading, wpautop( $text ) );

		tribe_notice(
			__FUNCTION__,
			$message,
			[
				'dismiss' => true,
				'type'    => 'warning',
			]
		);
	}

	/**
	 * Display dismissible notice about recurring events tickets, per event.
	 *
	 * @since 5.0.4
	 */
	public function maybe_display_classic_editor_ecp_recurring_tickets_notice() {
		$post_id = absint( tribe_get_request_var( 'post' ) );

		if ( empty( $post_id ) ) {
			return;
		}

		// Warning: Changing this would invalidate all past dismissals.
		$slug_prefix = 'tribe_notice_classic_editor_ecp_recurring_tickets';
		$notice_slug = sprintf( '%s-%d', $slug_prefix, $post_id );

		// Bail if this notice was previously dismissed for this TEC event.
		if ( Tribe__Admin__Notices::instance()->has_user_dimissed( $notice_slug ) ) {
			return;
		}

		/** @var Tribe__Tickets__Editor__Template__Overwrite $template_overwrite */
		$template_overwrite = tribe( 'tickets.editor.template.overwrite' );

		if (
			! function_exists( 'tribe_is_recurring_event' )
			|| ! tribe_is_recurring_event( $post_id )
			|| ! tribe_events_has_tickets( $post_id )
			|| ! $template_overwrite->has_classic_editor( $post_id )
		) {
			return;
		}

		$heading = sprintf(
			// Translators: %1$s: dynamic "Tickets" text, %2$s: dynamic "Event" text.
			_x(
				'%1$s for Recurring %2$s',
				'heading for classic editor notice if Events Calendar Pro event has tickets',
				'event-tickets'
			),
			tribe_get_ticket_label_plural( $slug_prefix ),
			tribe_get_event_label_singular()
		);

		$text = sprintf(
			// Translators: %1$s: dynamic "event" text, %2$s: dynamic "ticket" text, %3$s: dynamic "tickets" text, %4$s: dynamic "RSVP" text, %5$s: dynamic "Ticket" text.
			_x(
				'Heads up! You saved a recurring %1$s with a %2$s. Please note that we do not currently support recurring %3$s. Only the first instance of this recurring series will have your %4$s or %5$s displayed.',
				'text for classic editor notice if Events Calendar Pro event has tickets',
				'event-tickets'
			),
			tribe_get_event_label_singular_lowercase(),
			tribe_get_ticket_label_singular_lowercase( $slug_prefix ),
			tribe_get_ticket_label_plural_lowercase( $slug_prefix ),
			tribe_get_rsvp_label_singular( $slug_prefix ),
			tribe_get_ticket_label_singular( $slug_prefix )
		);

		$message = sprintf(
			'<h3>%1$s</h3>%2$s',
			esc_html( $heading ),
			wpautop( esc_html( $text ) )
		);

		tribe_notice(
			$notice_slug,
			$message,
			[
				'dismiss' => true,
				'type'    => 'warning',
			]
		);
	}

	/**
	 * Display a notice for the user about missing support if ET+ supported commerce providers are active
	 * but ET+ is not.
	 *
	 * @since 4.7
	 */
	public function maybe_display_plus_commerce_notice() {
		if ( class_exists( 'Tribe__Tickets_Plus__Main' ) ) {
			return;
		}

		$plus_link = add_query_arg(
			[
				'utm_source'   => 'plugin-install',
				'utm_medium'   => 'plugin-event-tickets',
				'utm_campaign' => 'in-app',
			],
			'https://theeventscalendar.com/product/wordpress-event-tickets-plus/'
		);

		$plus = sprintf(
			'<a target="_blank" rel="noopener nofollow" href="%s">%s</a>',
			esc_attr( $plus_link ),
			esc_html__( 'Event Tickets Plus', 'event-tickets' )
		);

		$plus_commerce_providers = [
			'woocommerce/woocommerce.php'                       => __( 'WooCommerce', 'event-tickets' ),
			'easy-digital-downloads/easy-digital-downloads.php' => __( 'Easy Digital Downloads', 'event-tickets' ),
		];

		foreach ( $plus_commerce_providers as $path => $provider ) {
			if ( ! is_plugin_active( $path ) ) {
				continue;
			}

			$message = sprintf(
				// translators: %1$s: The ticket commerce provider (WooCommerce, etc); %2$s: The Event Tickets Plus plugin name and link.
				esc_html__( 'Event Tickets does not support ticket sales via third party ecommerce plugins. If you want to sell tickets with %1$s, please purchase a license for %2$s.', 'event-tickets' ),
				esc_html( $provider ),
				$plus
			);

			// Wrap in <p> tag.
			$message = sprintf( '<p>%s</p>', $message );

			tribe_notice( "event-tickets-plus-missing-{$provider}-support", $message, 'dismiss=1&type=warning' );
		}
	}

	/**
	 * Display a notice when Tickets Commerce is enabled, yet a checkout page is not setup properly
	 *
	 * @since TBD
	 */
	public function maybe_display_tickets_commerce_checkout_setting_notice() {
		// If we're not on our own settings page, bail.
		if ( Tribe__Settings::$parent_slug !== tribe_get_request_var( 'page' ) ) {
			return;
		}

		// If tickets commerce not enabled, bail.
		if ( ! tec_tickets_commerce_is_enabled() ) {
			return;
		}

		// If checkout page is set and has the appropriate shortcode, bail.
		$checkout = new Commerce\Checkout();
		$checkout_page_id  = (int) $checkout->get_page_id();
		$checkout_page = get_post( $checkout_page_id );
		$checkout_shortcode = Commerce\Shortcodes\Checkout_Shortcode::get_wp_slug();
		if ( ! empty( $checkout_page ) && has_shortcode( $checkout_page->post_content, $checkout_shortcode ) ) {
			return;
		}

		$notice_link = sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
			esc_url( 'https://evnt.is/1axv' ),
			esc_html__( 'Learn More', 'event-tickets' )
		);
		$notice_header = esc_html__( 'Set up your checkout page', 'event-tickets' );
		$notice_text = sprintf( 
			// translators: %1$s: Link to knowledgebase article.
			esc_html__( 'In order to start selling with Tickets Commerce, you\'ll need to set up your checkout page. Please configure the setting on Settings > Payments and confirm that the page you have selected has the proper shortcode. %1$s', 'event-tickets' ),
			$notice_link
		);
		$notice_message_html = sprintf(
			'<p><strong>%1$s</strong></p><p>%2$s</p>',
			$notice_header,
			$notice_text
		);

		tribe_notice(
			'event-tickets-tickets-commerce-checkout-not-set',
			$notice_message_html,
			[
				'dismiss' => false,
				'type'    => 'error',
			]
		);
	}

	/**
	 * Display a notice when Tickets Commerce is enabled, yet a success page is not setup properly
	 *
	 * @since TBD
	 */
	public function maybe_display_tickets_commerce_success_setting_notice() {
		// If we're not on our own settings page, bail.
		if ( Tribe__Settings::$parent_slug !== tribe_get_request_var( 'page' ) ) {
			return;
		}

		// If tickets commerce not enabled, bail.
		if ( ! tec_tickets_commerce_is_enabled() ) {
			return;
		}

		// If success page is set and has the appropriate shortcode, bail.
		$success = new Commerce\Success();
		$success_page_id  = (int) $success->get_page_id();
		$success_page = get_post( $success_page_id );
		$success_shortcode = Commerce\Shortcodes\Success_Shortcode::get_wp_slug();
		if ( ! empty( $success_page ) && has_shortcode( $success_page->post_content, $success_shortcode ) ) {
			return;
		}

		$notice_link = sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
			esc_url( 'https://evnt.is/1axv' ),
			esc_html__( 'Learn More', 'event-tickets' )
		);
		$notice_header = esc_html__( 'Set up your order success page', 'event-tickets' );
		$notice_text = sprintf( 
			// translators: %1$s: Link to knowledgebase article.
			esc_html__( 'In order to start selling with Tickets Commerce, you\'ll need to set up your order success page. Please configure the setting on Settings > Payments and confirm that the page you have selected has the proper shortcode. %1$s', 'event-tickets' ),
			$notice_link
		);
		$notice_message_html = sprintf(
			'<p><strong>%1$s</strong></p><p>%2$s</p>',
			$notice_header,
			$notice_text
		);

		tribe_notice(
			'event-tickets-tickets-commerce-success-not-set',
			$notice_message_html,
			[
				'dismiss' => false,
				'type'    => 'error',
			]
		);
	}
}
