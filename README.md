esLog
=====

This is my fork of the esLog ownCloud app. This fork should send ownCloud users activity to Graphite using liuggio/statsd-php-client. 

From now the hooks set are:
- Read file
- Write file

#
# Installation instructions
#
This app requires the liuggio/statsd-php-client.

Once installed, place the vendor in the root path of the app (ie: apps/eslog/vendor)

Go to your admin and acive the app then you can configure Elasticsearch in the admin panel



#
# Tests
#

Owncloud 
* OC 8
