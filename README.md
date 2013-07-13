# How to setup project locally

__1.__ Clone project from github: `git clone https://github.com/boo1ean/network.git`

__2.__ Go to project dir and run: `./composer.phar install`

__3.__ Add new site record to apache conf `sudo gedit /etc/apache2/sites-available/network` (will create new file if doesn't exist)
And put:

```ApacheConf
<VirtualHost *:80>
	ServerName your-servername-here
	DocumentRoot /path/to/network/public
	<Directory /path/to/network/public>
		DirectoryIndex index.php
		AllowOverride All
		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>
```

__4.__ Add new record to hosts file due to have access to local server through alias `sudo gedit /etc/hosts` and add new line:


__NOTE:__ alias should be same as ServerName from apache virtual host config

```
127.0.0.1 your-servername-here
```

__5.__ Enable mod_rewrite for apache: `sudo a2enmod rewrite`

__6.__ Enable newly added site: `sudo a2ensite network`

__7.__ Restart apache: `sudo service apache2 restart`
