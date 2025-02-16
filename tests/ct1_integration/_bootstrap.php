<?php

// Ensure the CT1 code branch is enabled.
use TEC\Common\Monolog\Logger;
use TEC\Events\Custom_Tables\V1\Activation;
use TEC\Tickets\Provider;


$tec_support = dirname( __DIR__, 3 ) . '/the-events-calendar/tests/_support';
Codeception\Util\Autoload::addNamespace( 'Tribe\Events\Test', $tec_support );

// Let's  make sure Views v2 are activated if not.
putenv( 'TEC_TICKETS_COMMERCE=1' );
putenv( 'TEC_CUSTOM_TABLES_V1_DISABLED=0' );
$_ENV['TEC_CUSTOM_TABLES_V1_DISABLED'] = 0;
add_filter( 'tec_events_custom_tables_v1_enabled', '__return_true' );
tribe()->register( TEC\Events\Custom_Tables\V1\Provider::class );
tribe()->register( Provider::class );
// Run the activation routine to ensure the tables will be set up independently of the previous state.
Activation::activate();
tribe()->register( TEC\Events\Custom_Tables\V1\Full_Activation_Provider::class );
// The logger has already been set up at this point, remove all handlers to silence it.
$logger = tribe( Logger::class );
$logger->setHandlers( [] );
tribe()->make(Provider::class)->register_ct1_providers();