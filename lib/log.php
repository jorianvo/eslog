<?php
require_once 'apps/eslog/vendor/autoload.php';
use Liuggio\StatsdClient\StatsdClient,
    Liuggio\StatsdClient\Factory\StatsdDataFactory,
    Liuggio\StatsdClient\Sender\SocketSender,
    Liuggio\StatsdClient\Service\StatsdService;
use GeoIp2\Database\Reader;

class OC_esLog {

  public function __construct(){

  }

  // This function will gather the stats and send them statsd
  // The path is the path of the file that is read or written
  // currently this is not used. The action tells us the action
  // performed e.g. 'Webdav put request' and the prefix is the
  // statsd prefix we give to the metric in order to easily find
  // it in graphite
  public static function sendToStatsd($path,$prefix){
    // Get host and port from gui (admin)
    $host = OC_Appconfig::getValue('eslog', 'eslog_host', '127.0.0.1');
    $port = OC_Appconfig::getValue('eslog', 'eslog_port', '8125');
    $proto = OC_Appconfig::getValue('eslog', 'eslog_proto', 'udp');

    // Create the udp socket to send the data to statsd
    $sender = new SocketSender($host, $port, $proto);

    // Create the client which sends to data to statsd using the sender
    $client  = new StatsdClient($sender);
    $factory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');
    $service = new StatsdService($client, $factory);

    // Get ip and city of origin
    //if(isset($_SERVER["REMOTE_ADDR"])) {
    //  $ip = $_SERVER["REMOTE_ADDR"];
    //} else {
    //  $ip = '';
    //}

    // For testing, a browser plugin can be used to set this
    // header so the implementation can be tested for multiple countries
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = "";
    }

    // Ip can be invalid or a local address, if so set country to unknown
    // Otherwise we can go ahead and resolv country
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
      $locator = "Unknown";
    } else {
      // As filtering the private range works only for ipv4 we can still get no
      // location from the db, this case is handled by the IpToCountry method
      $locator = self::IpToLocator($ip);
    }

    //throw new \Exception("country of origin = $country");

    // Check if file is read or written
    $service->increment($prefix.$locator);

    // Send the data over the socket to statsd
    $service->flush();
  }

  // This function will return the locator of a given (valid, non-private)
  // ip address in the form <country>.<city> or just Unknown if the country
  // cannot be determined
  private static function IpToLocator($ip) {
    $reader = new Reader('/usr/local/share/GeoIP/GeoLite2-Country.mmdb');

    // Get the record of the corrsponding ip
    // The country can still be unknown e.g. if the $ip is a link local ipv6
    // address, so if the $record throws a 'GeoIp2\Exception\AddressNotFoundException'
    // Set the country to Unknown.
    try {
      $record = $reader->country($ip);
      $country = $record->country->name;

      // Country does exists, so now find the city
      $city = self::IpToCity($ip);

      // Only in this case we can return a country and city
      return $country.".".$city;
    } catch (GeoIp2\Exception\AddressNotFoundException $e) {
      // No country found, hence city makes no sense either
      return $country = "Unknown";
    }
  }

  // This function will return the corresponding city given an ip address
  private static function IpToCity($ip) {
    $reader = new Reader('/usr/local/share/GeoIP/GeoLite2-City.mmdb');

    // Just to be safe, also catch the 'GeoIp2\Exception\AddressNotFoundException'
    // if for some reason the city is unknown (we know for sure the country exists)
    try {
      $record = $reader->city($ip);

      // Country does exists, so now find the city
      $city = $record->city->name;
    } catch (GeoIp2\Exception\AddressNotFoundException $e) {
      $city = "Unknown";
    }

    return $city;
  }
}
