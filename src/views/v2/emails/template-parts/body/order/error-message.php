<?php
/**
 * Event Tickets Emails: Order Error Message
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/emails/template-parts/body/order/error-message.php
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

// @todo @codingmusician @juanfra Replace hardcoded data with dynamic data.

?>
<tr>
	<td style="font-size:14px;font-weight:400;padding-top:10px">
		The following attempted purchase has failed because:
	</td>
</tr>
<tr>
	<td style="color:#da394d;font-size:14px;font-weight:700;padding:24px 0 40px">
		Stripe payment processing was unsuccessful
	</td>
</tr>