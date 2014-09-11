* `.htaccess` - Rewrites `/` to `/library/`
* `admin/`
	* `content/`
		* `edit/` - See [edit.md]() for more information
			* `index.php` - Displays the video/series editor
			* `save.php` - Saves the changes into the database and redirects to show the changed file.
		* `upload/` - See [upload.md]() more for information
			* `cancel.php` - Deletes any partially uploaded fragments when an upload is cancelled.
			* `index.php` - The uploader interface
			* `upload_lib.php` - A few PHP functions used by both `cancel.php` and `uploadChunk.php`
			* `uploadChunk.php` - Receives chunks from [Resumable.js](../libraries.md#Resumablejs) and combines them into a complete file.
		* `delete.php` - Deletes videos (confirming beforehand).
		  If a series is being deleted, it recursively deletes the contained videos.
		* `template_serieseditor.php` - renders `templates/include_videoeditor.php`,
		to be inserted into a dialog box by `addNewSeries()``
		(in`public_html/static/videoeditor/SeriesCompletions.js`).
* `auth/`
	* `login.php` - Handles logins using mod_ldap (see [login.md]())
* `completions.js.php` - Returns data to be used in autocompleting for the editor.
* `content.php` - Serves the files, using mod_xsendfile.
* `contents.php` - Displays lists of videos arranged by metadata
* `fsck.php` - Runs a lot of consistency and sanity checks on the database and files, to identify various problems and errors.
  [fsck.md]() for details.
* `library.php` - Displays a complete list of videos, as well as the contents of a series and individual videos.
  (Basically, anything with an ID.)
* `static/` - Static (non-PHP) resources of various types
	* `admin/content/upload.css` - Stylesheet for upload interface
	* `lib/` - See [libraries.md](../libraries.md#JavaScript) for a complete list of libraries and what they do
	* `videoeditor/`
		* TODO document this
	* `global.css` - CSS which is applied to every page
	* `large_preloader.gif` - a largish animated GIF spinner
