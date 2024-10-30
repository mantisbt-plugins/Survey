<?php
$delete_id	= $_REQUEST['bug_id'];
$query		= "delete from {plugin_Survey_surveyresults} where bug_id= $delete_id ";
$result		= db_query( $query );
print_header_redirect( 'plugin.php?page=Survey/manage_Survey_page' );