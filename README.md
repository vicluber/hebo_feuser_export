This is an extension tested on Typo3 10.4

This extension is a good example for adding a Symfony command that you can execute with the Scheduler on Typo3.

This extension exports the fe_users table to a chosen folder with a chosen filename.

You can execute the command running it with Typo3's scheduler

Or with the typo3 script.In my case this script is placed on the folder /var/www/html/typo3/sys.../core/bin/
Example: ./typo3 export:users "folder" "filename"