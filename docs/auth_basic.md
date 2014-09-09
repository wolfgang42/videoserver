Many browsers use a separate plug-in to display video and don't pass
credentials to it. This means that videos protected by basic auth cannot be played.
As a workaround, videoserver provides support for PHP session-based
verification of a basic auth login. (`/admin` does not have this problem, and can be protected with basic auth directly.)

1. **User visits http://video.example.com/library/1.**
	Because they have not yet logged in, they have no session and are
2. **redirected to http://video.example.com/auth/login.**
	The web server has been configured to require basic authentication to access this page. Therefore,
3. **the user logs in** and accesses `public_html/auth/login.php`, which creates a session and
4. **redirects back to http://video.example.com/library/1**, which checks for the session and lets them access the server.

[auth_form](auth_form.md) is better, but only works if you have Apache 2.4 or better (it's not supported by Apache 2.2).

# Setup
1. Configure your webserver to require basic authentication for `pubic_html/auth`. (This is the hard bit. If you're using Apache, they have a [tutorial](http://httpd.apache.org/docs/current/howto/auth.html) on how to do this.)
2. Set `AUTH_BASIC` to `true` in `config.php`.

# Related discussions
* [safari 6.0.1 cannot load video or audio that requires basic authentication](https://discussions.apple.com/message/19820341)
* [Safari 5.1 HTML5 HTTP basic access authentication issue video does not load](https://discussions.apple.com/message/16530646)
* [Problem with video on mobile when there's basic authentication](http://jalbum.net/forum/thread.jspa?threadID=45172)
