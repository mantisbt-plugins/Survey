# Survey version 1.2.1
This plugin allows sending of a survey request once the issue reaches a specific status.

Installation is like any other plugin:
1. Download the plugin from Github
2. Copy the directory structure to the plugin directory of your Mantis installation.
3. Within Mantis, go to Manage / Manage plugins
4. Click on install next to the Survey plugin (to be found in the Available plugin section).
5. You can configure @ which status the request should be send to the reporter of the issue and in addition you can define who is allowed to see the results
That's all, enjoy

## Configuration
- Selection of status which will trigger the invitation for the Survey
- Selection of the user level that can view the management page of survey results
- Select the projects for which the survey should be triggered
	- for now a comme separated list which holds  the project_id's. Default value = '0' which means all projects.
- Set the max number of entries per page when looking @ the survey results ( between 5 & 100 )


## ToDo
- Beautify project selection

## Support
Issues/requests can be registered on Github:
https://github.com/mantisbt-plugins/Survey

## Changes
version<br>
1.0.0	30-10-2024	Initial release<br>
1.1.0	31-10-2024	Included management cockpit<br>
1.2.0	01-11-2024	Included option to activate the survey plugin for one or more projects<br>
1.2.1	01-11-2024	Added option to search in the resultset<br>
