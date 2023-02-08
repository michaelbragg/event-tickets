<?php
/**
 * Event Tickets Emails: Main template > Header for the preview.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/emails/template-parts/header/header-preview.php
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
 */

// If we're not on preview, bail.
if ( empty( $preview ) ) {
	return;
}

$this->template( 'template-parts/header/head/styles' );

?>
<div class="tec-tickets__email-body">

		<?php $this->template( 'template-parts/header/top-link' ); ?>

		<table role="presentation" class="tec-tickets__email-table-main">

			<?php $this->template( 'template-parts/body/header' ); ?>

			<tr>
				<td>
					<table role="presentation" class="tec-tickets__email-table-content">
