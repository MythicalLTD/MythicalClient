# Ubuntu 20.04 & 22.04 

## Dependency Installation
In this guide, we will install the required dependencies for MythicalClient. 

```bash
# Add "add-apt-repository" command
apt -y install software-properties-common curl apt-transport-https ca-certificates gnupg

# Add additional repositories for PHP, Redis, and MariaDB
LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php

# Add Redis official APT repository
curl -fsSL https://packages.redis.io/gpg | sudo gpg --dearmor -o /usr/share/keyrings/redis-archive-keyring.gpg

echo "deb [signed-by=/usr/share/keyrings/redis-archive-keyring.gpg] https://packages.redis.io/deb $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/redis.list

# MariaDB repo setup script can be skipped on Ubuntu 22.04
curl -sS https://downloads.mariadb.com/MariaDB/mariadb_repo_setup | sudo bash

# Update repositories list
apt update

# Install the rest of dependencies
apt install -y mariadb-server nginx tar unzip zip git redis-server nano cron certbot python3-certbot-nginx chrony curl dos2unix

## Installing composer package manager
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

## Download Files
The first step in this process is to create the folder where the client will live and then move ourselves into that newly created folder. Below is an example of how to perform this operation.
```bash
mkdir -p /var/www/mythicalclient
cd /var/www/mythicalclient
```
Once you have created a new directory for the client and moved into it you'll need to download the client files. This is as simple as using curl to download our pre-packaged content. Once it is downloaded you'll need to unpack the archive and then set the correct permissions on the `logs/` and `public/` directories. 
```bash
curl -Lo mythicalclient.zip https://github.com/mythicalltd/mythicalclient/releases/latest/download/mythicalclient.zip
unzip -o mythicalclient.zip -d /var/www/mythicalclient
```

## Installation
Now that all of the files have been downloaded we need to configure some core aspects of the client.

### Database Configuration
You will need a database setup and a user with the correct permissions created for that database before continuing any further. See below to create a user and database for your MythicalClient installation. 

```bash
mariadb -u root -p 
# Make sure to replace <yourPassword> with a strong password!
CREATE USER 'mythicalclient'@'127.0.0.1' IDENTIFIED BY '<yourPassword>';
CREATE DATABASE mythicalclient;
GRANT ALL PRIVILEGES ON mythicalclient.* TO 'mythicalclient'@'127.0.0.1' WITH GRANT OPTION;
exit
```

### Environment Configuration
MythicalClient's core environment is easily configured using a few different CLI commands built into the app. This step will cover setting up things such as settings and database credentials.

```bash
cd /var/www/mythicalclient
# Convert the script to a unix file
dos2unix arch.bash
# Compile the cli for the specific os you are using
sudo bash arch.bash
./MythicalClient -environment:setup # Setup the permissions and the config files
./MythicalClient -key:generate # Reset the encryption key
./MythicalClient -environment:database # Setup the database connection
./MythicalClient -migrate # Migrate the database
./MythicalClient -environment:setup # Start a custom setup for the dash

sudo systemctl enable --now redis-server
```

### Crontab Configuration
Setting up cron jobs will be really important; this is not an optional step: the cron jobs will allow the client to process data internally, and manage the queue system. First, check if crontab is installed by typing `crontab -e` in your terminal. Or, if you are using a hosting service, check if your host supports crontab. If you are on a terminal, run:
```text
* * * * * php /var/www/mythicalclient/crons/main.php >> /dev/null 2>&1
```

# Creating SSL Certificates
To begin, we will install certbot, a simple script that automatically renews our certificates and allows much easier creation of them.

Then, in the command below, you should replace example.com with the domain you would like to generate a certificate for. When you have multiple domains you would like certificates for, simply add more `-d anotherdomain.com` flags to the command. You can also look into generating a wildcard certificate but that is not covered in this tutorial.

### HTTP challenge
HTTP challenge requires you to expose port 80 for the challenge verification.

```bash
# Nginx
certbot certonly --nginx -d example.com
# Standalone - Use this if neither works. Make sure to stop your webserver first when using this method.
certbot certonly --standalone -d example.com
```

### DNS challenge
DNS challenge requires you to create a new TXT DNS record to verify domain ownership, instead of having to expose port 80. The instructions are displayed when you run the certbot command below.

```bash
certbot -d example.com --manual --preferred-challenges dns certonly
```

### Auto Renewal
You'll also probably want to configure the automatic renewal of certificates to prevent unexpected certificate expirations. You can open crontab with `sudo crontab -e` and add the line from below to the bottom of it for attempting renewal every day at 23 (11 PM).

```text
0 23 * * * certbot renew --quiet --deploy-hook "systemctl restart nginx"
```

# WebServer
First, remove the default NGINX configuration.

```bash
rm /etc/nginx/sites-enabled/default
```

Now, you should paste the contents of the file below, replacing <domain> with your domain name being used in a file called `MythicalClient.conf` and place the file in `/etc/nginx/sites-available`

```nginx
server {
    listen 80;
    server_name <domain>;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name <domain>;

    root /var/www/mythicalclient/public;
    index index.php;

    access_log /var/www/mythicalclient/logs/mythicalclient.app-access.log;
    error_log  /var/www/mythicalclient/logs/mythicalclient.app-error.log error;

    # allow larger file uploads and longer script runtime
    client_max_body_size 100m;
    client_body_timeout 120s;

    sendfile off;

    # SSL Configuration - Replace the example <domain> with your domain
    ssl_certificate /etc/letsencrypt/live/<domain>/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/<domain>/privkey.pem;
    ssl_session_cache shared:SSL:10m;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers "ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384";
    ssl_prefer_server_ciphers on;

    # See https://hstspreload.org/ before uncommenting the line below.
    # add_header Strict-Transport-Security "max-age=15768000; preload;";
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Robots-Tag none;
    add_header Content-Security-Policy "frame-ancestors 'self'";
    add_header X-Frame-Options DENY;
    add_header Referrer-Policy same-origin;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param PHP_VALUE "upload_max_filesize = 100M \n post_max_size=100M";
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTP_PROXY "";
        fastcgi_intercept_errors off;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_read_timeout 300;
        include /etc/nginx/fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### Enabling Configuration
Once you've created the file above, simply run the commands below.
```bash
sudo ln -s /etc/nginx/sites-available/MythicalClient.conf /etc/nginx/sites-enabled/MythicalClient.conf
sudo systemctl restart nginx
```

## Congratulations!
Well done! You have successfully installed MythicalClient. You can now send out the link and have people create servers to their hearts content!