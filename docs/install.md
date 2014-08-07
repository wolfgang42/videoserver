1. **Install dependencies** <br/>
  You will need the following packages (for Debian-based systems):
`apache2` `php5` `libapache2-mod-xsendfile` `php5-sqlite` `sqlite3`
2. **Enable & Configure Apache Modules** <br/>
  The following Apache modules must be installed, enabled,
  and configured :
    * **mod_rewrite** <br/>
        ```
        AllowOverride All
        ```
    * **mod_xsendfile** <br/>
        ```
        XSendFile On
        XSendFilePath /path/to/videoserver/content
        ```
    * **mod_negotiation** <br/>
        ```
        AddType application/x-httpd-php .php
        ```
3. **Create database** <br/>
  ```bash
  $ sqlite3 content.sqlite < database.sql
  ```
4. **Check permissions** <br/>
  The following files need to be writable by the web server:
	* `content.sqlite`
	* `content/`
	* `cache/`
5. **Configure authentication** <br/>
  If you want authentication, you will need to set up `public_html/auth/login.php`;
  otherwise, you will need to edit `includes.php` to remove the
  authentication requirement.
6. **Check installation** <br/>
  Visit http://<i>your-server</i>/fsck and make sure no errors are displayed.
