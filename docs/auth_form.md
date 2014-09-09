Videoserver provides support for [Apache's mod_auth_form](http://httpd.apache.org/docs/current/mod/mod_auth_form.html).
These are the directives which must be set in the Apache config to use
videoserver's auth_form integration, *after* you have already set up mod_auth_form.

**Make sure to change */path/to/videoserver* and *example.videoserver.com*
to the correct locations.**
```
<Directory "/path/to/videoserver/public_html">
	SessionCookieName session path=/
	ErrorDocument 401 /auth/login_form
</Directory>

<Directory /path/to/videoserver/public_html/auth>
	Satisfy Any
	Allow from all
</Directory>
<Directory /path/to/videoserver/public_html/static>
	Satisfy Any
	Allow from all
</Directory>

<Location /auth/logout>
	SetHandler form-logout-handler
	AuthFormLogoutLocation /
	# Set the cookie to expire in 1 second. (0 is never expire)
	SessionMaxAge 1
	# Set a cookie which will expire in 1 minute, saying that we have logged out
	# intentionally.
	RewriteEngine On
	RewriteRule .* - [CO=loggedout:y:videoserver.example.com:1]
</Location>
```
