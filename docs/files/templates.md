* `page/` - Leaf templates which are called in PHP and actually shown to the user
	* `admin/`
		* `content/`
			* `delete_confirm.twig` - "Are you sure you want to delete this video?"
			* `edit.twig` - Video editor. See [edit.md]() for information on how this works.
			* `upload.twig` - Uploader. See [upload.md]() for details.
	* `auth/`
		* `login_form.twig`
	* `contents.twig` - A list of tags. Uses `abstract_videolist.twig`
	* `library.twig` - A list of videos. Uses `abstract_videolist.twig`
	* `video.twig` - The page that actually shows the videos.
* `abstract_page.twig` - Generic blank page template, used by all other templates.
* `abstract_videolist.twig` - Template for any list of videos. Used by `contents.twig` and `library.twig`.
* `error.twig` - Generic error message.
* `include_videoeditor.twig` - *part* of a page (the video editor), used in `admin/content/edit.twig` and `admin/content/upload.twig`
* `mixin_authenticated.twig` - Mixin which should be used on authenticated pages. Contains the navigation bar.
* `mixin_requirejs.twig` - Mixin for pages which use [require.js](../libraries.md#requirejs).
  Allows them to define which libraries they require and code which should be run once all of the libraries
  are ready, and displays a spinner in the meanwhile.
