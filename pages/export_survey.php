<?php
$query = "select * from {plugin_Survey_surveyresults} s, {bug} b where b.id=s.bug_id order by bug_id desc";

$content ="";
$content .= lang_get('bug');
$content .= "|";
$content .= lang_get('summary');
$content .= "|";
$content .= lang_get('reporter');
$content .= "|";
$content .= plugin_lang_get('handler');
$content .= "|";
$content .= plugin_lang_get('score');
$content .= "|"; 
$content .= plugin_lang_get('good');
$content .= "|";
$content .= plugin_lang_get('wrong');
$content .= "|";
$content .= plugin_lang_get('improve');
$content .= "|";
$content .= lang_get('date_modified');
$content .= lang_get('time_hours');
$content .= "\r\n";

$result = db_query($query);
while ($t_row = db_fetch_array($result)) {
	
	$reporter  		= user_get_username( $row["reporter_id"] );
	if ( $row["reporter_id"] > 0 ) {
		$reportername= user_get_realname( $row["reporter_id"] );
		$reporter .= ' - '.$reportername;
	}
	$handler  		= user_get_username( $row["handler_id"] );
	if ( $row["handler_id"] > 0 ) {
		$handlername= user_get_realname( $row["handler_id"] );
		$handler .= ' - '.$handler;
	} else {
		$handler .= ' - Administrator';
	}
	$summary  		=  $row["summary"];
	
	$content .= $t_row["bug_id"] ;
	$content .= "|";
	$content .= $t_row["summary"] ;
	$content .= "|";
	$content .= $t_row["reporter"] ;
	$content .= "|";
	$content .= $t_row["handler"] ;
	$content .= "|";
	$content .= $t_row["score"] ;
	$content .= "|";
	$content .= $t_row["good"];
	$content .= "|";
	$content .= $t_row["wrong"] ;
	$content .= "|";
	$content .= $t_row["improve"] ;
	$content .= "\r\n";
}	
$content .= "\r\n";

header('Content-type: text/enriched');
header("Content-Disposition: attachment; filename=Export_Surveys.csv");
echo $content;
exit;
return;