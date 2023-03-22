<?php
/**
 * Event Tickets Emails: Order Attendee Info
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/emails/template-parts/body/order/attendee-info.php
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
	<td style="padding:50px 0">
		<table style="border-collapse:collapse;margin-top:10px">
			<tr style="border:1px solid #d5d5d5;color: #727272;font-size:12px;font-weight:400;line-height:24px;">
				<td style="padding:0 6px;text-align:left;" align="left">Attendee</td>
				<td style="padding:0 6px;text-align:center;" align="center">Type</td>
				<td style="padding:0 6px;text-align:right;" align="right">Ticket ID</td>
			</tr>
			<tr style="border:1px solid #d5d5d5;font-size:12px;font-weight:400;line-height:24px;">
				<td style="padding:0 6px;text-align:left;vertical-align:top" align="left">
					David Hickox<br>
					david@theeventscalendar.com<br>
					Shirt size - large<br>
					Backstage pass - yes
				</td>
				<td style="padding:0 6px;text-align:center;vertical-align:top" align="center">General Admission</td>
				<td style="padding:0 6px;text-align:right;vertical-align:top" align="right">17e4a14cec</td>
			</tr>
			<tr style="border:1px solid #d5d5d5;font-size:12px;font-weight:400;line-height:24px;">
				<td style="padding:0 6px;text-align:left;vertical-align:top" align="left">
					Matt Whitson<br>
					matt@aptv.org<br>
					Shirt size - small<br>
					Backstage pass - yes
				</td>
				<td style="padding:0 6px;text-align:center;vertical-align:top" align="center">General Admission</td>
				<td style="padding:0 6px;text-align:right;vertical-align:top" align="right">55e5e14w4</td>
			</tr>
		</table>
	</td>
</tr>