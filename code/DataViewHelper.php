<?php

class DataViewHelper
{
	/**
	 * Retrieves all database columns from a specific DataObject class - or multiple classes, if multiple parameters
	 * are given.
	 *
	 * The returned array contains quoted `table`.`column` as the value and column as the key. If the column is ambiguous,
	 * the key will be prefixed with the DataObject's class name and an underscore: class_column to make it unique.
	 *
	 * @param string DataObject class
	 * @param string Another DataObject class etc...
	 * @return string[]
	 */
	public static function DataObjectColumns()
	{
		$classes = func_get_args();
		$class_columns = [];
		$duplicate_column_check = [];
		foreach ($classes as $class)
		{
			$columns = ['ID'] + array_keys(DataObject::database_fields($class));
			$class_columns[$class] = $columns;
			foreach ($columns as $column)
			{
				if (!isset($duplicate_column_check[$column]))
				{
					$duplicate_column_check[$column] = false;
				}
				else
				{
					$duplicate_column_check[$column] = true;
				}
			}
		}
		$result_columns = [];
		foreach ($class_columns as $class => $columns)
		{
			foreach ($columns as $column)
			{
				$table = ClassInfo::table_for_object_field($class, $column);
				$qualified_column = "`$table`.`$column`";
				if ($duplicate_column_check[$column])
				{
					//The same column name is used in multiple classes. Prefix the column name with the class name to make it non-ambiguous.
					$alias_column = "{$class}_$column";
				}
				else
				{
					//The column name is unique. No need to add a prefix.
					$alias_column = $column;
				}
				$result_columns[$alias_column] = $qualified_column;
			}
		}
		return $result_columns;
	}
}