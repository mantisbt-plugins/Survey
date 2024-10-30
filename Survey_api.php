<?PHP
/**
 * Send a email with a link to handle the survey
 * @param int $p_bug_id
 */
 
function email_survey_link( $p_bug_id ) {
	$t_reporter_id = bug_get_field( $p_bug_id, 'reporter_id' );
	$t_sender_id = auth_get_current_user_id();
	$t_sender = user_get_name( $t_sender_id );
	$t_email = '';
	$t_sender_email = '';
	$t_subject = "[[SURVEY]]";
	$t_subject .= email_build_subject( $p_bug_id );
	$t_date = date( config_get( 'normal_date_format' ) );
	$p_message = plugin_lang_get( 'message' ) ;
	$t_email = user_get_email( $t_reporter_id );
	$t_sender_email = ' <' . current_user_get_field( 'email' ) . '>';
	$t_header = "\n" . lang_get( 'on_date' ) . " $t_date, $t_sender $t_sender_email " . plugin_lang_get( 'sent_you_this_survey_link_about' ) . ": \n\n";
	$t_contents = $t_header . string_get_bug_survey_url_with_fqdn( $p_bug_id, $t_email ) . " \n\n$p_message";

	if( ON == config_get( 'enable_email_notification' ) ) {
		email_store( $t_email, $t_subject, $t_contents );
	}
	
	if ( OFF == config_get( 'email_send_using_cronjob' ) ) {
		email_send_all();
	}

	return;
}
 

function string_get_bug_survey_url_with_fqdn( $p_bug_id ) {
	return config_get_global( 'path' ) . string_get_bug_survey_url( $p_bug_id );
}

function string_get_bug_survey_url( $p_bug_id ) {
	return 'plugin.php?page=Survey/fill_survey.php&id=' . $p_bug_id;
}