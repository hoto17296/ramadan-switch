<?php

if ( $TZ = getenv('TZ') ) date_default_timezone_set($TZ);

$pos = array(
  'lat' => (float)getenv('POS_LNG'),
  'lng' => (float)getenv('POS_LAT'),
);

$listener = getenv('LISTENER_URL');

$exit_after_emit = !! getenv('EXIT_AFTER_EMIT');

while (true) {
  $time = time();

  $sunrise = date_sunrise($time, SUNFUNCS_RET_STRING, $pos['lat'], $pos['lng']);
  if ( date('H:i', $time) === $sunrise ) {
    post($listener, array('status' => 'sunrise'));
    if ( $exit_after_emit ) exit;
  }

  $sunset = date_sunset($time, SUNFUNCS_RET_STRING, $pos['lat'], $pos['lng']);
  if ( date('H:i', $time) === $sunset ) {
    post($listener, array('status' => 'sunset'));
    if ( $exit_after_emit ) exit;
  }

  sleep(60);
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
