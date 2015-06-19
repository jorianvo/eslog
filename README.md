esLog
=====

This is my fork of the esLog ownCloud app. This fork should send ownCloud users activity to Graphite using [statsd-php-client](https://github.com/liuggio/statsd-php-client).

From now the hooks set are:
- Read file
- Write file

## Installation instructions (Linux)
This app requires the [statsd-php-client](https://github.com/liuggio/statsd-php-client). Before we can install it we need to get some dependencies. The first dependency is ``git`` so we can clone this repo:

    $ sudo apt-get update
    $ sudo apt-get install git

Then we need ``composer`` so we can use/install **statsd-php-client**. I opted for a global installation, but this is not necessary (just take at look at the installation [instructions](https://getcomposer.org/doc/00-intro.md) from composer):

	$ cd ~
    $ curl -sS https://getcomposer.org/installer | php
    $ sudo mv composer.phar /usr/local/bin/composer

Once installed, clone this repo in the apps directory of ownCloud (i.e. ``/var/www/owncloud/apps`` for a default ownCloud installation) and use composer to install **statsd-php-client**:

    $ cd /var/www/owncloud/apps
    $ sudo git clone https://github.com/jorianvo/eslog
    $ cd /var/www/owncloud/apps/eslog
    $ sudo composer install

The last step is to download the MaxMind free GeoLite2 databases, we need both since we are interested in both the country and the city of origin. To download the databases:

    $ sudo mkdir -p /usr/local/share/GeoIP
    $ cd /usr/local/share/GeoIP
    $ sudo wget http://geolite.maxmind.com/download/geoip/database/GeoLite2-{Country,City}.mmdb.gz
    $ sudo gunzip /usr/local/share/GeoIP/GeoLite2-{Country,City}.mmdb.gz

The last step is to enable the app in ownCloud.

### Tested with:

- ownCloud 8.0.4
