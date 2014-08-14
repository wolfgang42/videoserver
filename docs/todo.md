## Files
* `Readme.md` - Write a little blurb with information about the program.
* `inc_unauthenticated.php` - Update twig to *not* use debug mode
* `inc_unauthenticated.php` - update `redirect()` to check for permanence, etc.
* `includes.php` - Only redirect to login if authentication is required
* `templates/` - Organize into pages and blank templates.
* `templates/directory.twig` has a lot of CSS that isn't used any more.
* `templates/contents.twig` and `templates/library.twig` are remarkably similar. Do we actually need both of them?
* `templates/requirejs.twig` - Display an error message if JavaScript is disabled.
* `templates/admin/content/edit.twig` - There's a message saying *TODO this is a bit of a kluge*
* `public_html/auth/login.php` - Currently only supports mod_ldap
* `public_html/admin/content/upload/upload_lib.php` has a note: *TODO: I don't actually recall why there are two slightly different requirements for identifiers and filenames...*
* `public_html/static/videoeditor/` needs to be reorganized
* `public_html/static/videoeditor/` needs to be documented
* `public_html/fsck.php` - Make sure SQLITE_DB is in a writable folder.

## Docs
* libraries.md
	* Composer
	* Twig
	* **all** JavaScript libraries
* files/includes.md
* files/edit.md
* files/upload.md
* files/login.md

## Misc
* Write a little blurb about how videos/serieses are set up
* Turn uploadChunk.php into an example of how to use resumable.js in PHP
* Use bower to manage JavaScript libraries
* Upgrade PHP dependencies with composer
* Several pages have embedded CSS rather than using external stylesheets
* The uploader doesn't complain if the server returns a 500 error. (To reproduce, make `cache/upload/tmp` unwritable.)
* Fix newlines
* Remove any console.log lines
* Document CONTENT_DIR, SQLITE_DB, etc.
* Document requirement that SQLITE_DB's folder must be writable. - http://magnatecha.com/sqlite-3-and-php-unable-to-open-database-file/
* Document support for authentication
* Document latent support for multiple tracks, subtitles, etc.
* Document authentication support
	* Basic auth
	* mod_auth_form
