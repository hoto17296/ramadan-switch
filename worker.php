<?php

if ( $TZ = getenv('TZ') ) date_default_timezone_set($TZ);

function start() {
  while (true) {
    check(time());
    sleep(60);
  }
}

function check($time) {
  $lat = (float)getenv('POS_LAT');
  $lng = (float)getenv('POS_LNG');
  $listener = getenv('LISTENER_URL');

  $sunrise = date_sunrise($time, SUNFUNCS_RET_STRING, $lat, $lng);
  if ( date('H:i', $time) === $sunrise ) post($listener, array('status' => 'sunrise'));

  $sunset = date_sunset($time, SUNFUNCS_RET_STRING, $lat, $lng);
  if ( date('H:i', $time) === $sunset ) post($listener, array('status' => 'sunset'));
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

start();
