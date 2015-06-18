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
  // currently this is not used. The action is either 'File read'
  // or 'File write' and determines what value to send to statsd
  public static function sendToStatsd($path,$action){
    // Create the udp socket to send the data to statsd
    $sender = new SocketSender('localhost', 8125, 'udp');

    // Create the client which sends to data to statsd using the sender
    $client  = new StatsdClient($sender);
    $factory = new StatsdDataFactory('\Liuggio\StatsdClient\Entity\StatsdData');
    $service = new StatsdService($client, $factory);

    // Get ip and city of origin
    //if(isset($_SERVER["REMOTE_ADDR"])) {
    //     $ip = $_SERVER["REMOTE_ADDR"];
    //}

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = "none";
    }

    // Ip can be invalid or a local address, if so set country to unknown
    // Otherwise we can go ahead and resolv country
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
      $country = "Unknown";
    } else {
      // As filtering the private range works only for ipv4 we can still get no
      // location from the db, this case is handled by the IpToCountry method
      $country = self::IpToCountry($ip);
    }

    //throw new \Exception("country of origin = $country");

    // Check if file is read or written
    if ($action == "File read"){
      $service->increment('read');
    } elseif ($action == "File write"){
      $service->increment("files.writes.".$country);
    }

    // Send the data over the socket to statsd
    $service->flush();
  }

  // This function will return the corresponding country given a city
  private static function IpToCountry($ip) {
    $reader = new Reader('/usr/local/share/GeoIP/GeoLite2-Country.mmdb');

    // Get the record of the corrsponding ip
    // The country can still be unknown e.g. if the $ip is a link local ipv6
    // address, so if the $record throws a 'GeoIp2\Exception\AddressNotFoundException'
    // Set the country to Unknown.
    try {
        $record = $reader->country($ip);
        $country = $record->country->name;
    } catch (GeoIp2\Exception\AddressNotFoundException $e) {
        $country = "Unknown";
    }

  // This function will check if $haystack ends with $needle, if so it will return true, else false
  // substr compares $haystack from offset -strlen($needle) (thus from the end of the string)
  // up to the end of $haystack
  // Originally found at http://theoryapp.com/string-startswith-and-endswith-in-php/
  private static function endsWith($haystack, $needle) {
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    return $country;
  }
}
