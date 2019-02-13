/**
 * External dependencies
 */
import React, { Fragment } from 'react';
import PropTypes from 'prop-types';
import AutosizeInput from 'react-input-autosize';

/**
 * Wordpress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Tooltip } from '@moderntribe/common/elements';
import { Clipboard, Pencil } from '@moderntribe/common/icons';
import './style.pcss';

const TicketContainerHeaderTitle = ( {
	hasAttendeeInfoFields,
	isDisabled,
	isSelected,
	onTempTitleChange,
	tempTitle,
	title,
} ) => {
	const clipboard = hasAttendeeInfoFields && (
		<Tooltip
			labelClassName="tribe-editor__ticket__container-header-clipboard-tooltip"
			label={ <Clipboard /> }
			text={ __(
				'This ticket has Additional Fields configured.',
				'event-tickets',
			) }
		/>
	);

	return (
		<div className="tribe-editor__ticket__container-header-title">
			{
				isSelected
					? (
						<Fragment>
							<AutosizeInput
								className="tribe-editor__ticket__container-header-title-input"
								value={ tempTitle }
								placeholder={ __( 'Ticket Type', 'event-tickets' ) }
								onChange={ onTempTitleChange }
								disabled={ isDisabled }
							/>
							{ clipboard }
						</Fragment>
					)
					: (
						<Fragment>
							<h3 className="tribe-editor__ticket__container-header-title-label">
								{ title }
							</h3>
							{ clipboard }
							<Pencil />
						</Fragment>
					)
			}
		</div>
	);
}

TicketContainerHeaderTitle.propTypes = {
	hasAttendeeInfoFields: PropTypes.bool,
	isDisabled: PropTypes.bool,
	isSelected: PropTypes.bool,
	onTempTitleChange: PropTypes.func,
	tempTitle: PropTypes.string,
	title: PropTypes.string,
};

export default TicketContainerHeaderTitle;
