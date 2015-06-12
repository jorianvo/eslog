esLog
=====

This is my fork of the esLog ownCloud app. This fork should send ownCloud users activity to Graphite using [statsd-php-client](https://github.com/liuggio/statsd-php-client).

From now the hooks set are:
- Read file
- Write file

## Installation instructions (Linux)
This app requires the [statsd-php-client](https://github.com/liuggio/statsd-php-client). Before we can install it we need to get some dependencies. The first dependency is ``git`` so we can clone the **statsd-php-client** repo:

    $ sudo apt-get install git

Then we need ``composer`` so we can use/install **statsd-php-client**. I opted for a global installation, but this is not necessary (just take at look at the installation [instructions](https://getcomposer.org/doc/00-intro.md) from composer):

    $ curl -sS https://getcomposer.org/installer | php
    $ sudo mv composer.phar /usr/local/bin/composer

Once installed, clone this repo in the apps directory of ownCloud (i.e. ``/var/www/owncloud/apps`` for a default ownCloud installation) and use composer to install **statsd-php-client**:

    $ cd /var/www/owncloud/apps
    $ git clone https://github.com/jorianvo/eslog
    $ composer install

Place the vendor directory in the **statsd-php-client** in the cloned repo:

    $ sudo cp -r ~/statsd-php-client/vendor/ /var/www/owncloud/apps/eslog/

Now this app should be ready to go!

### ownCloud


#
# Tests
#

Owncloud
* OC 8
