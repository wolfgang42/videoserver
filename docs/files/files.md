* `cache/` - Temporary files which can be safely deleted
	* `twig/` - Compiled [Twig](../libraries.md#Twig) templates
	* `upload/` - Files which have been uploaded but not yet saved (see [upload.md]() for details)
		* `dest/` - Completely uploaded files
		* `tmp/` - Partial chunks of uploaded files
	* `.gitignore`
* `composer.json`, `composer.lock` - Files for [Composer](../libraries.md#Composer)
* `content/` - Where the video files are stored
* `content.sqlite` - Sqlite3 database where information about the videos is stored
* `database.sql` - SQL template for the `content.sqlite` file.
* `docs/` - Documentation
* `inc_unauthenticated.php` - Included on every page, for functions which are needed when the user has not yet authenticated. See [includes.md](includes.md#Unauthenticated) for details.
* `includes.php` - Included on pages which *do* require the user to be authenticated. See [includes.md](includes.md#Authenticated) for details.
* `lib/` - Libraries. This folder is managed by [Composer](../libraries.md#Composer); please don't edit it by hand!
	* `composer` - [Composer](../libraries.md#Composer)
	* `twig`- [Twig](../libraries.md#Twig)
* [`public_html/`](public_html.md) - PHP files which are accessible via the web server. See [public_html.md]() for details.
* [`templates/`](templates.md) - [Twig](../libraries.md#Twig) templates which are used to display the actual page. See [templates.md]() for details.
