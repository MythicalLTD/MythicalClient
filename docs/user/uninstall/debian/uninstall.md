# Debian 11 & 12

### Removing the files
So first, we have to delete the files we saved on your server!
```bash
rm -r /var/www/mythicalclient
```

### Removing the database
Next, we have to drop and delete the database to make sure that the data is gone!
```bash
DROP TABLE mythicalclient;
DROP USER 'mythicalclient'@'127.0.0.1';
```

### Removing the certificates
Now we need to remove the SSL certificates from the server!
```bash
sudo certbot delete --cert-name <domain>
```

### Removing the web server configuration!
And as a last step, we have to delete the web server configuration file!
```bash
sudo rm /etc/nginx/sites-available/MythicalClient.conf 
sudo rm /etc/nginx/sites-enabled/MythicalClient.conf
```
