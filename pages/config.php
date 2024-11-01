<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( plugin_lang_get( 'title' ) );
layout_page_begin( 'config_page.php' );
print_manage_menu();
?>
<br/>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 
<br>
<form action="<?php echo plugin_page( 'config_edit' ) ?>" method="post">

<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo plugin_lang_get( 'title') . ': ' . plugin_lang_get( 'config' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<table class="table table-bordered table-condensed table-striped"> 


	<?php echo form_security_field( 'plugin_Survey_config_update' ) ?>

<tr  >
	<td class="category">
	<?php echo plugin_lang_get( 'results_view_threshold' ) ?>
	</td>
	<td>
	<select name="results_view_threshold">
	<?php print_enum_string_option_list( 'access_levels', plugin_config_get( 'results_view_threshold'  ) ) ?>;
	</select> 
	</td>
</tr>

<tr >
	<td class="category">
	<?php echo plugin_lang_get( 'trigger' ) ?>
	</td>
	<td >
	<select name="trigger">
	<?php print_enum_string_option_list( 'status', plugin_config_get( 'Survey_status'  ) ) ?>;
	</select> 
	</td>
</tr>

<tr >
	<td class="category">
	<?php echo plugin_lang_get( 'projects' ) ?>
	</td>
	<td >
	<input type="text" id="projects" name="projects"  value =<?php echo plugin_config_get( 'Survey_projects' )  ?>>
	</td>
</tr>

<tr >
	<td class="category">
	<?php echo plugin_lang_get( 'entries' ) ?>
	</td>
	<td >
	<input type="number" id="entries" name="entries" min="5" max="100" value =<?php echo plugin_config_get( 'results_per_page' ) ?>>
	</td>
</tr>

<div class="widget-toolbox padding-8 clearfix">
	<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo lang_get( 'change_configuration' )?>" >
</div>
	</table>
</div>
</div>
</form>
</div>
</div>
<?php
layout_page_end();