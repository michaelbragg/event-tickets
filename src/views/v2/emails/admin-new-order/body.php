<?php
/**
 * Event Tickets Emails: Completed Order Template.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/emails/admin-new-order/body.php
 *
 * See more documentation about our views templating system.
 *
 * @link https://evnt.is/tickets-emails-tpl Help article for Tickets Emails template files.
 *
 * @version TBD
 *
 * @since TBD
 *
 * @var Tribe_Template  $this  Current template object.
 * @var Module           $provider              [Global] The tickets provider instance.
 * @var string           $provider_id           [Global] The tickets provider class name.
 * @var \WP_Post         $order                 [Global] The order object.
 * @var int              $order_id              [Global] The order ID.
 * @var bool             $is_tec_active         [Global] Whether `The Events Calendar` is active or not.
 */


$this->template( 'template-parts/body/title' );
$this->template( 'admin-new-order/purchaser-details' );
$this->template( 'admin-new-order/event-title' );
$this->template( 'admin-new-order/ticket-totals' );
$this->template( 'admin-new-order/order-total' );
$this->template( 'admin-new-order/payment-info' );
$this->template( 'admin-new-order/attendee-info' );
