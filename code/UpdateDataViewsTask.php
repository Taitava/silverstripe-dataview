<?php

class UpdateDataViewsTask extends BuildTask
{
	/**
	 * Set this to true if you want to clutter your /dev/tasks list with yet-another-hmm-what-does-this-button-do-thing.
	 *
	 * @conf bool
	 */
	private static $can_run_from_web_browser = false;
	
	public function isEnabled()
	{
		if (Director::is_cli()) return true;
		return (bool) static::config()->can_run_from_web_browser;
	}
	
	public function run($request)
	{
		echo 'Updating database views... ';
		DataViewBuilder::BuildOrDropAllViews();
		echo "done\n";
	}
}