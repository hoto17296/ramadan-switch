# Ramadan Switch
Send webhooks to sunset and sunrise times.

[![](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/hoto17296/ramadan-switch)

## Environment variables
| key | description |
|---|---|
| `TZ` | The timezone of place from which the sunrise and sunset time is taken. |
| `LISTENER_URL` | The URL to which webhook will be sent. |

## Webhook request example
```
POST /switch HTTP/1.1
Host: example.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 13

status=sunset
```
