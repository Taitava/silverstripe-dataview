# silverstripe-dataview

This module allows you to create MySQL views that are automatically built and updated during the `/dev/build` process (or via a separate `BuildTask`, if you prefer not to do this during `/dev/build`).

Example:
```PHP
class MyView extends DataView
{
	public static function getViewStatement()
        {
        	//Borrow SQL from a DataList:
        	return MyDataObject::get()->filter('SoftDeleted', false);
        	
        	//Custom SQL:
        	return new SQLSelect(['Book.Title','Author.Name'],'Book')->addLeftJoin('Author','Book.AuthorID = Author.ID');
        	
        	//The quick and dirty way: hard coded SQL:
        	return 'SELECT quantity, price, quantity*price AS amount FROM Product';
        }
}
```

Now just run `/dev/build` in your browser or `php framework/cli-script.php dev/tasks/UpdateDataViewsTask` in terminal. Now you have your new view defined in the database. Note that you do need to gave CREATE VIEW and DROP VIEW permissions in the database! DROP VIEW is mandatory for updating the structure of existing views.

Unfortunately this module does not currently offer any method to read data from views, but I'm planning to do some research to determine what would be the most meaningful way to achieve this.


## Roadmap

For version 1.0 I would like this module to have:
 - DataList style way to filter and iterate records from views
 - DataObject style way to read properties from records (no write ability. Writing should be done through regular DataObjects or custom SQL commands)
 - Support for SilverStripe 4.x
 - A better documentation
 - Probably something more...

## Contribution

If you have any ideas about how to improve this module or any questions, I would be glad to hear them! :) Please raise an issue or create a pull request - which ever you like.
