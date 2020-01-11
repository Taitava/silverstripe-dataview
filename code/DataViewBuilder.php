<?php

// Support SilverStripe versions lower than 3.7:
if (!class_exists('SS_Object')) class_alias('Object', 'SS_Object');

class DataViewBuilder extends SS_Object
{
	/**
	 * @conf bool If true, views are automatically created/replaced/dropped during the dev/build process.
	 */
	private static $update_views_on_dev_build = true;
	
	public static function BuildOrDropAllViews()
	{
		$data_view_classes = ClassInfo::subclassesFor('DataView');
		array_shift($data_view_classes); //Remove 'DataView' from the array
		foreach ($data_view_classes as $data_view_class)
		{
			static::BuildOrDropView($data_view_class);
		}
	}
	
	/**
	 * Creates the view in the database, or replaces it with a new one if it exists already. This should be run every
	 * time you make changes to any database tables, because even if the statement of the view is not changed, MySQL
	 * needs to update the view's column references if any columns are added/removed to/from normal database tables.
	 *
	 * @param string $class
	 */
	public static function BuildView($class)
	{
		$view_name = Convert::raw2sql($class::ViewName());
		$view_statement = static::view_statement_as_string($class);
		$query = 'CREATE OR REPLACE VIEW `' . $view_name . '` AS ' . $view_statement;
		// die($query);
		DB::query($query);
	}
	
	public static function DropView($class)
	{
		$view_name = Convert::raw2sql($class::ViewName());
		Db::query("DROP VIEW IF EXISTS `$view_name`");
	}
	
	/**
	 * Checks whether the view is obsolete. If not, creates or updates it in the database. If it is obsolete, drops
	 * it from the database.
	 *
	 * @see DataView::isObsolete()
	 * @param string $class
	 */
	public static function BuildOrDropView($class)
	{
		if ($class::isObsolete())
		{
			static::DropView($class);
		}
		else
		{
			static::BuildView($class);
		}
	}
	
	/**
	 * @param string $class
	 * @return string
	 */
	private static function view_statement_as_string($class)
	{
		$view_statement = $class::getViewStatement();
		if (is_string($view_statement)) return $view_statement;
		return $view_statement->sql();
	}
	
}
