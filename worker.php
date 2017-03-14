<?php

if ( $TZ = getenv('TZ') ) date_default_timezone_set($TZ);

$listener = getenv('LISTENER_URL');

while (true) {
  if ( $status = check(time()) ) post($listener, compact('status'));
  sleep(60);
}

function check($time) {
  $location = (object)(new DateTimeZone( date_default_timezone_get() ))->getLocation();

  $sunrise = date_sunrise($time, SUNFUNCS_RET_STRING, $location->latitude, $location->longitude);
  if ( date('H:i', $time) === $sunrise ) return 'sunrise';

  $sunset = date_sunset($time, SUNFUNCS_RET_STRING, $location->latitude, $location->longitude);
  if ( date('H:i', $time) === $sunset ) return 'sunset';

  return null;
}

function post($url, array $data = []) {
  $data = http_build_query($data, '', '&');

  $header = array(
    'Content-Type: application/x-www-form-urlencoded',
    'Content-Length: ' . strlen($data),
  );

  $context = array(
    'http' => array(
      'method'  => 'POST',
      'header'  => implode("\r\n", $header),
      'content' => $data,
    )
  );

  return file_get_contents($url, false, stream_context_create($context));
}
