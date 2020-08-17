## Url Monitor Demo

### Overview

Url Monitor written in Laravel for demo purposes.


- The jobs are triggered to be ran every minute. 
- Urls are fetched using Guzzle using promises (several at once)
- There is a bug in Guzzle which causes all promises to fail when one is causing an exception
 (when e.g. Linkedin returns status 999), patch is added in composer.json
- There are some tests 

### Api overview

1. POST /monitors - add endpoint to monitor. `stats` parameter allows to retrieve stats immediately in X-Stats header
2. GET /monitors/{url}: get last 10 minutes of data.
