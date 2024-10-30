<?php

# authenticate
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

# Read results
form_security_validate( 'plugin_Survey_config_update' );
$f_threshold	= gpc_get_int( 'results_view_threshold',70 );
$f_trigger		= gpc_get_int( 'trigger',70 );
# update results
plugin_config_set( 'trigger', $f_trigger );
plugin_config_set( 'results_view_threshold', $f_threshold );

form_security_purge( 'plugin_Survey_config_update' );

# redirect
print_header_redirect( plugin_page( 'config', true ) );
