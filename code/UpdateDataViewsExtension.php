<?php

class UpdateDataViewsExtension extends DataExtension
{
	public function requireDefaultRecords()
	{
		static $updated = false;
		if ($updated) return; //Prevent performing this update multiple times during a single request.
		$updated = true;
		
		if (!DataViewBuilder::config()->update_views_on_dev_build) return; //Automatic view updating is disabled
		
		if (Director::is_cli())
		{
			$li_open = ' * ';
			$li_close = "\n";
		}
		else
		{
			$li_open = '<li>';
			$li_close = '</li>';
		}
		
		echo $li_open . 'Updating database views... ';
		DataViewBuilder::BuildOrDropAllViews();
		echo 'done' . $li_close;
	}
}
