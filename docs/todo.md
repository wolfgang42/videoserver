## Files
* `Readme.md` - Write a little blurb with information about the program.
* `inc_unauthenticated.php` - Update twig to *not* use debug mode
* `inc_unauthenticated.php` - update `redirect()` to check for permanence, etc.
* `templates/abstract_videolist.twig` has a lot of CSS that isn't used any more.
* `templates/page/contents.twig` and `templates/library.twig` are remarkably similar. Do we actually need both of them?
* `templates/mixin_requirejs.twig` - Display an error message if JavaScript is disabled.
* `public_html/auth/login.php` - Currently only supports mod_ldap
* `public_html/static/videoeditor/` needs to be reorganized
* `public_html/static/videoeditor/` needs to be documented
* `templates/admin/content/upload.twig` - simultaneousUploads is set to 1, but this is a workaround to keep uploads from breaking.
* Fix other TODOs:
	* `docs/files/public_html.md`:		* `TODO document this
	* inc_unauthenticated.php`:	# TODO check permanence, return HTML, etc.
	* `public_html/admin/content/upload/upload_lib.php`:// TODO: I don't actually recall why there are two slightly different requirements for identifiers and filenames...
	* `public_html/content.php`:		# TODO check if the user is authorized to view this video
	* `public_html/library.php`:					//TODO 'series' =>
	* `templates/error.twig`:	{# TODO suggestions etc? #}
	* `templates/page/admin/content/edit.twig`:		// TODO this is a bit of a kluge
	* `templates/page/admin/content/upload.twig`:	videos = ko.observableArray([]); // TODO load in existing videos
	* `templates/page/admin/content/upload.twig`:					// TODO callback to cancel upload
	* `templates/page/video.twig`:	{# <!-- TODO metadata --> #}

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
* Document latent support for multiple tracks, subtitles, etc.
