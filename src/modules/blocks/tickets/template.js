/**
 * External dependencies
 */
import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

/**
 * Internal dependencies
 */
import TicketsDashboard from './dashboard/container';
import TicketsContainer from './container/container';
import TicketControls from './controls/container';
import './style.pcss';

class Tickets extends PureComponent {
	static propTypes = {
		clientId: PropTypes.string,
		hasProviders: PropTypes.bool,
		header: PropTypes.string,
		isSelected: PropTypes.bool,
		isSettingsOpen: PropTypes.bool,
	};

	render() {
		const {
			isSelected,
			hasProviders,
			isSettingsOpen,
			clientId,
		} = this.props;

		return (
			<div
				className={ classNames(
					'tribe-editor__tickets',
					{ 'tribe-editor__tickets--selected': isSelected },
					{ 'tribe-editor__tickets--settings-open': isSettingsOpen },
				) }
			>
				<TicketsContainer isSelected={ isSelected } />
				{ hasProviders && <TicketsDashboard isSelected={ isSelected } clientId={ clientId } /> }
				<TicketControls />
			</div>
		);
	}
}

export default Tickets;
