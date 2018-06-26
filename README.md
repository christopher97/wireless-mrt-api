# Wireless Mobile Software Engineering API
Repository for API of wireless mobile software engineering + pervasive computing project

## Framework and Language Used

[![Laravel](https://camo.githubusercontent.com/5ceadc94fd40688144b193fd8ece2b805d79ca9b/68747470733a2f2f6c61726176656c2e636f6d2f6173736574732f696d672f636f6d706f6e656e74732f6c6f676f2d6c61726176656c2e737667)](https://laravel.com/)
[![PHP](https://avatars1.githubusercontent.com/u/25158?s=200&v=4)](http://php.net/)

## Currently Available Routes

Login (POST)
```sh
api/login
```

```javascript
{
  "email": "your_email"
  "password": "your_password"
}
```

Get list of stations (GET)
```sh
api/stations
```

Get station detail (GET)
```sh
api/station/{id}
```

Nearest station (GET)
```sh
api/nearby?lat=current_lat&long=current_long
```

substitute current_lat and current_long with latitude and longitude
example:
```sh
api/nearby?lat=-6.226525&long=106.801323
```

ETA to current station (GET)
```sh
api/eta{id}
```