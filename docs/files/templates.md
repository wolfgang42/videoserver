* `admin/`
	* `content/`
		* `delete_confirm.twig` - "Are you sure you want to delete this video?"
		* `edit.twig` - Video editor. See [edit.md]() for information on how this works.
		* `upload.twig` - Uploader. See [upload.md]() for details.
* `contents.twig` - A list of tags. Uses `directory.twig`
* `directory.twig` - Template for any list of videos. Used by `contents.twig` and `library.twig`.
* `error.twig` - Generic error message.
* `library.twig` - A list of videos. Uses `directory.twig`
* `page.twig` - Generic blank page template, used by all other templates.
* `requirejs.twig` - Extends `page.twig` for pages which use [require.js](../libraries.md#requirejs)
* `video.twig` - The page that actually shows the videos.
* `videoeditor.twig` - *part* of a page (the video editor), used in `admin/content/edit.twig` and `admin/content/upload.twig`
