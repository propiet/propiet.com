# Propiet Web Readme


# Development environment

## PHP support:

* ``sudo apt-get install php5-fpm php5-curl php5-intl php5-mysql``

## Web server configuration

* Check README.md on propiet-api's repo

## Install composer.php:

* ``curl -sS https://getcomposer.org/installer | php``

## Install vendor libraries:

* ``php composer.phar install``

## Configure the Symphony application

* Browse to ``http://localhost/config.php`` and follow instructions

## Install nodejs:

* ``sudo add-apt-repository ppa:chris-lea/node.js``
* ``sudo apt-get update``
* ``sudo apt-get install python-software-properties python g++ make nodejs``

## Install less:

* ``ln -s /usr/lib/node_modules/ ~/.node_libraries``
* ``sudo npm install -g less``

### Compile less files to css:

* ``php app/console assetic:dump``

### Include assets located in your bundles' Resources/public folder:

*``php app/console assets:install``

### Development mode:

For development run with the ``--watch`` flag:

* ``php app/console assetic:dump --watch``

### Troubleshooting

If you get a
   ``Error Output:``
   ``sh: 1: /usr/local/bin/node: not found``

Create a symlink to your node binary:

* ``sudo ln -s /usr/bin/node /usr/local/bin/node``

### Development API URL

* As this application does not have API parameterized URLs (they are hardcoded) we need to resolve api.propiet.com to localhost (the Python app). Add the following line to ``/etc/hosts``:
   ``127.0.0.1 api.propiet.com``

## Production / Staging

### Get the source code

* Clone this repo inside /path/to/propiet/com/dir: ``mkdir -p /path/to/propiet/com/dir && cd /path/to/propiet/com/dir && git clone git@git.devartis.com:propiet-com .``

### nginx configuration

* Map nginx config file: ``sudo ln -s /path/to/propiet/com/dir/setup/prod/propiet.com.conf /etc/nginx/sites-available/ && sudo ln -s /etc/nginx/sites-available/propiet.com.conf /etc/nginx/sites-enabled/``
* Restart the service: ``sudo serice nginx restart``
* Access to the app http://www.propiet.com/
