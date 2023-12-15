# Ubuntu 20.04 & 22.04 

This documentation covers the process for updating within the 0.x series of releases. This means updating from — for example — 0.0.1 to 0.0.2

### Take a backup
So first we are going to take a backup of the client and the database structure using:
```bash
cd /var/www/mythicalclient
mariadb-dump -p mythicalclient > mythicalclient_backup.sql
cd /var/www
zip -r mythicalclient.zip mythicalclient/
```

### Download the Update
The first step in the update process is to download the new client files from GitHub. The command below will download the release archive for the most recent version of MythicalClient.
```bash
cd /var/www/mythicalclient
curl -Lo mythicalclient.zip https://github.com/mythicalltd/mythicalclient/releases/latest/download/mythicalclient.zip
unzip -o mythicalclient.zip -d /var/www/mythicalclient
dos2unix arch.bash
sudo bash arch.bash
rm /var/www/mythicalclient/public/FIRST_USER # Remove this
```

### Update Dependencies
After you've downloaded all of the new files you will need to upgrade the core components of the dash. To do this, simply run the commands below and follow any prompts.

```bash
composer install --no-dev --optimize-autoloader
```

### Database Updates
You'll also need to update your database schema for the newest version of MythicalClient.
```bash
./MythicalClient -migrate
```

### Set Permissions
The last step is to set the proper owner of the files to be the user that runs your webserver. 
```bash
chown -R www-data:www-data /var/www/mythicalclient/*
```

### Done 
You are done, and now you should be running the latest version of MythicalClient.