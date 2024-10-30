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
$content .= "\r\n";

$result = db_query($query);
while ($t_row = db_fetch_array($result)) {
	
	$reporter  		= user_get_username( $t_row["reporter_id"] );
	if ( $t_row["reporter_id"] > 0 ) {
		$reportername= user_get_realname( $t_row["reporter_id"] );
		$reporter .= ' - '.$reportername;
	}
	$handler  		= user_get_username( $t_row["handler_id"] );
	if ( $t_row["handler_id"] > 0 ) {
		$handlername= user_get_realname( $t_row["handler_id"] );
		$handler .= ' - '.$handler;
	} else {
		$handler .= ' - Administrator';
	}
	switch($t_row['survey_score'] ) {
		case 1:
			$scoretxt = plugin_lang_get('bad');
			break;
		case 2:
			$scoretxt = plugin_lang_get('poor');
			break;
		case 3:
			$scoretxt = plugin_lang_get('average');
			break;
		case 4:
			$scoretxt = plugin_lang_get('ok');
			break;
		case 1:
			$scoretxt = plugin_lang_get('excellent');
			break;
	}

	$content .= $t_row["bug_id"] ;
	$content .= "|";
	$content .= $t_row["summary"] ;
	$content .= "|";
	$content .= $reporter ;
	$content .= "|";
	$content .= $handler ;
	$content .= "|";
	$content .= $scoretxt ;
	$content .= "|";
	$content .= $t_row["survey_good"];
	$content .= "|";
	$content .= $t_row["survey_wrong"] ;
	$content .= "|";
	$content .= $t_row["survey_improve"] ;
	$content .= "|";
	$content .= $t_row["survey_created"] ;
	$content .= "\r\n";
}	
$content .= "\r\n";

header('Content-type: text/enriched');
header("Content-Disposition: attachment; filename=Export_Surveys.csv");
echo $content;
exit;
return;