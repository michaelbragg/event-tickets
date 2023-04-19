<?php
/**
 * Event Tickets Emails: Main template > Body > Ticket > Security code.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/emails/template-parts/body/ticket/security-code.php
 *
 * See more documentation about our views templating system.
 *
 * @link https://evnt.is/tickets-emails-tpl Help article for Tickets Emails template files.
 * If you are looking for Event related templates, see in The Events Calendar plugin.
 *
 * @version 5.5.9
 *
 * @since 5.5.9
 *
 * @var Tribe__Template $this             Current template object.
 * @var Email_Abstract  $email            The email object.
 * @var bool            $preview          Whether the email is in preview mode or not.
 * @var bool            $is_tec_active    Whether `The Events Calendar` is active or not.
 * @var array           $tickets          The list of tickets.
 * @var array           $ticket           The ticket object.
 * @var WP_Post|null    $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

if ( empty( $ticket['security_code'] ) ) {
	return;
}

?>
<div class="tec-tickets__email-table-content-ticket-security-code">
	<?php echo esc_html( $ticket['security_code'] ); ?>
</div>
