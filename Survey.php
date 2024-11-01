<?php
require_once( 'plugins/Survey/Survey_api.php' ); 
class SurveyPlugin extends MantisPlugin {
	
		/** Error constants */
	const ERROR_WRONG_USER = "error_wrong_user";
	const ERROR_WRONG_STATUS = "error_wrong_status";
	const ERROR_SURVEY_DONE = "error_survey_done";



	function register() {
		$this->name = plugin_lang_get( 'title' );
		$this->description = plugin_lang_get( 'description' );
		$this->page = 'config';
		$this->version = '1.2.0';
		$this->requires = array( 'MantisCore' => '2.0.0', );
		$this->author = 'Cas Nuy';
		$this->contact = 'cas@nuy.info';
		$this->url = 'https://github.com/mantisbt-plugins/Survey';
	}

	function config() {
		return array(
			'Survey_status'				=> 90,		// Closed
			'results_view_threshold'	=> 70,		// Manager	
			'results_per_page'			=> 25,
			'Survey_projects'			=> '0',			
			);
	}

	function init() {
		plugin_event_hook( 'EVENT_UPDATE_BUG', 'SendSurvey' );
		plugin_event_hook( 'EVENT_MENU_MANAGE', 'managemenu' );
	}

	function managemenu() {
		return array('<a href="'. plugin_page( 'manage_Survey_page.php' ) . '">' . plugin_lang_get( 'survey' ) . '</a>' );
    }


	function SendSurvey($p_event,$p_bug_data,$p_bug_data_new)   {
		# check if new status is equal to Survey_status
		$check= plugin_config_get( 'Survey_status');
		$t_curstat = $p_bug_data->status;
		$t_newstat = $p_bug_data_new->status;
		$t_projects = plugin_config_get( 'Survey_projects');
		if ( $t_projects <> '0' ) {
			# we have a filter on projects
			$t_project_id = $p_bug_data->project_id ;
			$selprojects = explode ( ",",$t_projects );
			if ( !in_array( $t_project_id, $selprojects ) ) {
				return;
			}
		}
		if ( ( $t_curstat <> $t_newstat ) and ( $t_newstat == $check ) ) {
			# survey request can only be registered once so if we already have results skip the email
			$t_bug_id	= $p_bug_data->id ;
			$sql		= "select * from {plugin_Survey_surveyresults} where bug_id = $t_bug_id";
			$result		= db_query($sql);
			$t_count	= db_num_rows ($result );
			if ( $t_count < 1 ){
				# now send an email with the survey request
				# email send to reporter of task
				$resultmail = email_survey_link( $t_bug_id );
			}
		}
	}

function errors() {
		return [
			self::ERROR_WRONG_USER => plugin_lang_get( self::ERROR_WRONG_USER ),
			self::ERROR_WRONG_STATUS => plugin_lang_get( self::ERROR_WRONG_STATUS ),
			self::ERROR_SURVEY_DONE => plugin_lang_get( self::ERROR_SURVEY_DONE ),
		];
	}

	
	function schema() {
		return array(
			array( 'CreateTableSQL', array( plugin_table( 'surveyresults' ), "
						id					I       NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
						bug_id				I       DEFAULT NULL,
						survey_score		I       DEFAULT NULL,
						survey_good			C(250)	DEFAULT NULL,
						survey_wrong		C(250)	DEFAULT NULL,
						survey_improve		C(250)	DEFAULT NULL,
						survey_created		date		DEFAULT NULL
						" ) ),
		);
	}

}