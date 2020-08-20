## Url Monitor Demo

### Overview

Url Monitor written in Laravel for demo purposes.
Allows to add several urls at once, which are then monitored by a background job.
The job will gather stats for the urls for a given period (10 minutes by default).
Currently there is no authentication.


- The jobs are triggered to be ran every minute. 
- Urls are fetched using Guzzle using promises (several at once)
- There is a bug in Guzzle which causes all promises to fail when one is causing an exception
 (when e.g. Linkedin returns status 999), patch is added in composer.json
- There is no authentication for now 
- There are some tests 

### Api overview

1. POST /monitors - add endpoint to monitor. `stats` parameter allows to retrieve stats immediately in X-Stats header
2. GET /monitors/{url}: get last 10 minutes of data.
