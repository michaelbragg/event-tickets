<?php
/**
 * Event Tickets Emails: Tickets > Body.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/emails/ticket/body.php
 *
 * See more documentation about our views templating system.
 *
 * @link https://evnt.is/tickets-emails-tpl Help article for Tickets Emails template files.
 * If you are looking for Event related templates, see in The Events Calendar plugin.
 *
 * @version TBD
 *
 * @since TBD
 *
 * @var Tribe__Template $this               Current template object.
 * @var Email_Abstract  $email              The email object.
 * @var string          $heading            The email heading.
 * @var string          $title              The email title.
 * @var bool            $preview            Whether the email is in preview mode or not.
 * @var string          $additional_content The email additional content.
 * @var bool            $is_tec_active      Whether `The Events Calendar` is active or not.
 * @var WP_Post|null    $event              The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

$this->template( 'template-parts/body/title' );

$this->template( 'template-parts/body/tickets' );
