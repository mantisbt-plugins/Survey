<?php
$t_bug_id	= gpc_get_int( 'id' );

# check if user filling the Survey is reporter of the issue
$t_cur_user	= auth_get_current_user_id();
$t_user = bug_get_field( $t_bug_id, 'reporter_id' );
if ($t_cur_user <> $t_user ){
	# raise error 
	plugin_error( SurveyPlugin::ERROR_WRONG_USER );
}
# check if status is correct
$check= plugin_config_get( 'Survey_status');
$t_status = bug_get_field( $t_bug_id, 'status' );
if ($check <> $t_status ){
	# raise error 
	plugin_error( SurveyPlugin::ERROR_WRONG_STATUS );
}
# check if we already have results
# survey request can only be registered once so if we already have results skip this one
$sql		= "select * from {plugin_Survey_surveyresults} where bug_id = $t_bug_id";
$result		= db_query($sql);
$t_count	= db_num_rows ($result );
if ( $t_count > 0 ){
	# raise error 
	plugin_error( SurveyPlugin::ERROR_SURVEY_DONE );
}


layout_page_header( lang_get( 'plugin_format_title' ) );
layout_page_begin( );
?>

<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 

<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo  plugin_lang_get( 'title' ).': ' . plugin_lang_get( 'fill_survey' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<form action="<?php echo plugin_page( 'post_survey' ) ?>" method="post">
<input type="hidden" name="bug_id" value="<?php echo $t_bug_id;  ?>">
<table class="table table-bordered table-condensed table-striped"> 
<?php
$sql = "select * from {bug} where id = $t_bug_id";
$result = db_query($sql);
while ( $row = db_fetch_array( $result ) ) {
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
	?>
	<tr>
	<td ><?php  echo lang_get( 'reporter' ) ?>	</td>
	<td ><?php  echo $reporter ?>	</td>
	</tr>
	
	<tr>
	<td ><?php  echo plugin_lang_get( 'handler' ) ?>	</td>
	<td ><?php  echo $handler ?>	</td>
	</tr>	
	
	<tr>
	<td ><?php  echo lang_get( 'summary' ) ?>	</td>
	<td ><?php  echo $t_bug_id .' => '.$summary ?>	</td>
	</tr>

	<tr>
		<td><?php  echo plugin_lang_get( 'score' ) ?></td>

<td>
<label><input type="radio" name='score' value="1" ><?php echo plugin_lang_get( 'bad' )?></label>
<label><input type="radio" name='score' value="2" ><?php echo plugin_lang_get( 'poor' )?></label>
<label><input type="radio" name='score' value="3" ><?php echo plugin_lang_get( 'average' )?></label>
<label><input type="radio" name='score' value="4" ><?php echo plugin_lang_get( 'ok' )?></label>
<label><input type="radio" name='score' value="5" ><?php echo plugin_lang_get( 'excellent' )?></label>
		</td>
	</tr>

	<tr>
	<td><?php  echo plugin_lang_get( 'good' ) ?></td>
		<td>
		<textarea rows = "3" cols = "125" name="good" ></textarea>
		</td>
	</tr>
	
	<tr>
	<td><?php  echo plugin_lang_get( 'wrong' ) ?></td>
		<td>
		<textarea rows = "3" cols = "125" name="wrong"></textarea>
		</td>
	</tr>
	
	<tr>
	<td><?php  echo plugin_lang_get( 'improve' ) ?></td>
		<td>
		<textarea rows = "3" cols = "125" name="improve"></textarea>
		</td>
	</tr>
	
	</div></td>

	<?PHP
}
?>
<tr>
<td class="center" colspan="2">

<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get( 'post_survey' ) ?>" />
</td>
</tr>

</table>
</div>
</div>
</div>
</div>
</div>
</div>
<?php
layout_page_end();