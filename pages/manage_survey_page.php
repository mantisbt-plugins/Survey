<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( lang_get( 'plugin_format_title' ) );
layout_page_begin( 'config.php' );
print_manage_menu();

# do we end up here searching for a specific survey result
$search_id		= gpc_get_int( 'bug_id',0 );


# pagination of results 
$per_page  = plugin_config_get( 'results_per_page' ) ;
if (isset($_GET["pages"])) {
	$pages = $_GET["pages"];
} else {
	$pages=1;
}

// Page will start from 0 and Multiple by Per Page
$start_from = ($pages-1) * $per_page;

$points = 0;
$query = "SELECT sum(survey_score) as points, count(*) as count from {plugin_Survey_surveyresults}";
$result		= db_query( $query );
while ( $row = db_fetch_array( $result ) ) {
	$points = $row["points"];
	$count	= $row['count']; 
}

if ( $count > 0) {
	$average = number_format($points/$count,1); 
} else {
	$average = 0;
}

//Using ceil function to divide the total records on per page
$total_pages = ceil($count / $per_page);

$link1 = "plugin.php?page=Survey/export_survey.php";
// create the resultset 
if ($search_id > 0 ) {
	$query 		= "select * from {plugin_Survey_surveyresults} s, {bug} b where b.id=s.bug_id and s.bug_id = $search_id";

} else {
	$query 		= "select * from {plugin_Survey_surveyresults} s, {bug} b where b.id=s.bug_id order by bug_id desc LIMIT $start_from, $per_page";
}
$result		= db_query( $query );

?>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 

<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo  plugin_lang_get( 'title' ).': ' . plugin_lang_get( 'manage' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 

<div id="nav-search" class="nav-search"><form class="form-search" method="post" action="plugin.php?page=Survey/manage_Survey_page.php"><span class="input-icon"><input type="text" name="bug_id" autocomplete="off" class="nav-search-input" placeholder="Issue #"><i class="fa fa-search ace-icon nav-search-icon" ></i></span></form></div>

<table class="table table-bordered table-condensed table-striped"> 
<form action="<?php echo plugin_page( 'ip_add' ) ?>" method="post">
<tr >
<td class="category" colspan="10">
</td>
</tr>
<br>
<tr>

<td class="form-title" colspan="2" >
<?php print_link_button( $link1, plugin_lang_get( 'export'  ) );?>
</td>
<td class="center" colspan="8" >
<b><big>
<?php
echo plugin_lang_get( 'txt1' ) ;
echo $average; 
echo plugin_lang_get( 'txt2' ) ;
echo $count; ?>
&nbsp
<?php
echo plugin_lang_get( 'survey' ) ;
 ?>
 </big></b>
</td>
</tr>

<tr class="row-category">
<td><div align="center"><?php echo lang_get( 'bug' ) ?></div></td>
<td><div align="center"><?php echo lang_get( 'summary' ); ?></div></td>
<td><div align="center"><?php echo lang_get( 'reporter' ) ?></div></td>
<td><div align="center"><?php echo plugin_lang_get( 'handler' ) ?></div></td>
<td><div align="center"><?php echo plugin_lang_get( 'score' ) ?></div></td>
<td><div align="center"><?php echo plugin_lang_get( 'good' ) ?></div></td>
<td><div align="center"><?php echo plugin_lang_get( 'wrong' ) ?></div></td>
<td><div align="center"><?php echo plugin_lang_get( 'improve' ) ?></div></td>
<td><div align="center"><?php echo lang_get( 'date_modified' ) ?></div></td>
<td></td>


</form>


<?php

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
	switch ($row['survey_score']) {
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
		case 5:
			$scoretxt = plugin_lang_get('excellent');
			break;
	}
	?>
	<tr>
	<td><div align="center"><?php echo $row["bug_id"] ?>	</div></td>
	<td><div align="center"><?php echo $row["summary"] ?></div></td>
	<td><div align="center"><?php echo $reporter ?></div></td>
	<td><div align="center"><?php echo $handler ?></div></td>
	<td><div align="center"><?PHP echo $scoretxt?>	</div></td>
	<td><div align="center"><?PHP echo $row["survey_good"]?>	</div></td>
	<td><div align="center"><?PHP echo $row["survey_wrong"]?>	</div></td>
	<td><div align="center"><?PHP echo $row["survey_improve"]?>	</div></td>
	<td><div align="center"><?PHP echo $row["survey_created"]?>	</div></td>
	<td><div align="center">
	<?PHP
	$link6 = "plugin.php?page=Survey/del_survey.php&bug_id=";
	$link6 .= $row["bug_id"]  ;
	print_link_button( $link6, plugin_lang_get( 'del_survey' ) );
	?>
	</div></td>
	</tr>
	<?PHP
}
//Going to first page
echo "<center><a href='plugin.php?page=Survey/manage_Survey_page.php&pages=1'>".'First Page '."</a>";
for ($i=1; $i<=$total_pages; $i++) {
	echo "<a href='plugin.php?page=Survey/manage_Survey_page.php&pages=".$i."'>".$i."</a>";
};
// Going to last page
echo "<a href='plugin.php?page=Survey/manage_Survey_page.php&pages=$total_pages'>".' Last Page'."</a></center>";
?>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<?php

layout_page_end();