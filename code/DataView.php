<?php

abstract class DataView extends ViewableData
{
	/**
	 * The view name which should be created in the database. Leave null if you want to automatically take the name
	 * from your DataView class name.
	 *
	 * @conf null|string
	 */
	private static $view_name = null;
	
	/**
	 * If your view gets obsolete and you have already stopped using it everywhere, you can override this method in your
	 * old view class and make it return true. When views are updated using the UpdateDatabaseViewsTask, all views whose
	 * isAbandoned() method returns false will get dropped from the database automatically.
	 *
	 * @return bool
	 */
	public static function isObsolete()
	{
		return false;
	}
	
	public static function ViewName()
	{
		$view_name = static::config()->view_name;
		if (!$view_name) $view_name = static::class;
		return $view_name;
	}
	
	/**
	 * @return DataQuery|SQLQuery|SQLSelect|DataList|string
	 * @throws Exception
	 */
	public static function getViewStatement()
	{
		throw new Exception(static::class . ' should override the getViewStatement() method and not call the parent method.');
	}
	
}
