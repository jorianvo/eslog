<?php if(basename($_SERVER['REQUEST_URI'])=='admin'): ?>
<form id="eslogsettings" method="post" action="#" class="section">
  <h2>Graphite Log</h2>
  <p>
  Send OwnCloud events to a <a href="http://graphite.wikidot.com/">Graphite</a> server.</br>

  <p>
    <input type="text" id="eslog_host" name="eslog_host" value="<?php echo $_['eslog_host']; ?>" size="50"/>
    <label for="eslog_host">Statsd server (Use the following format: fqdn|ip)</label>
  </p>
  <p>
    <input type="text" id="eslog_user" name="eslog_user" value="<?php echo $_['eslog_port']; ?>" size="50"/>
    <label for="eslog_user">Port number statsd listen on</label>
  </p>
  <p>
    <input type="submit" value="Save"/>
  </p>
</form>
<?php endif; ?>
