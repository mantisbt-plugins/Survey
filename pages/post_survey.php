<?php
$t_bug_id	= gpc_get_int( 'bug_id' );
$f_score	= gpc_get_int( 'score');
$f_good		= gpc_get_string( 'good' );
$f_wrong	= gpc_get_string( 'wrong' );
$f_improve	= gpc_get_string( 'improve' );
$curdate 	= date('Y-m-d H:i:s');
# check againif we already have results
# survey request can only be registered once so if we already have results skip this one
$sql		= "select * from {plugin_Survey_surveyresults} where bug_id = $t_bug_id";
$result		= db_query($sql);
$t_count	= db_num_rows ($result );
if ( $t_count > 0 ){
	# raise error 
	plugin_error( SurveyPlugin::ERROR_SURVEY_DONE );
}
# store the response in the DB
$sql = 'INSERT INTO {plugin_Survey_surveyresults} ( bug_id, survey_score,survey_good, survey_wrong, survey_improve, survey_created ) VALUES ( ' . db_param() . ',' . db_param() . ',' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ')';
db_query($sql, array( $t_bug_id, $f_score, $f_good, $f_wrong, $f_improve, $curdate ) );

# send email to inform someone about the posted survey
if ( ON == plugin_config_get( 'Survey_inform_result') ) {
	$postedmail = email_survey_post( $t_bug_id );
}

# return to view_all_bugs
print_header_redirect( 'view_all_bug_page.php' );