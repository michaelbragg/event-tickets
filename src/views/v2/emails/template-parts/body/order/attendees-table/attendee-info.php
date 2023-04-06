<?php
/**
 * Event Tickets Emails: Order Attendee Info
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/emails/template-parts/body/order/attendees-table/attendee-info.php
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
 * @var array            $order                 [Global] The order object.
 * @var int              $order_id              [Global] The order ID.
 * @var bool             $is_tec_active         [Global] Whether `The Events Calendar` is active or not.
 */

if ( empty( $attendee ) ) {
	return;
}

?>
<tr class="tec-tickets__email-table-content-order-attendee-info-row">
	<td class="tec-tickets__email-table-content-order-attendee-info tec-tickets__email-table-content-align-left" align="left">
		<?php $this->template( 'template-parts/body/order/attendees-table/attendee-name' ); ?>
		<?php $this->template( 'template-parts/body/order/attendees-table/attendee-email' ); ?>
		<?php $this->template( 'template-parts/body/order/attendees-table/custom-fields' ); ?>
	</td>
	<?php $this->template( 'template-parts/body/order/attendees-table/ticket-title' ); ?>
	<?php $this->template( 'template-parts/body/order/attendees-table/ticket-id' ); ?>
</tr>