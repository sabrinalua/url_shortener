Yii 2 Basic Project Template
============================
DB CONFIG
-------------
Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=dbname',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- use IP of localhost(usually 127.0.0.1) instead of 'localhost' when on local server. This is for migration purposes
- Refer to the README in the `tests` directory for information specific to basic application tests.

DB MIGRATION
-------------
cd to the path where the project is installed and run 
```
php yii migrate
```
DB TABLES
-------------------
- shortener 
    - [id(pk), url, key(unique), created_at]
    - stores details on traffic to key 
- access 
    -  [id(pk), key_id, ip_address, city, region, country, geolocation, os, browser, browser_version, device_type, access_date]
    - stores details on traffic to shortened key

EXTERBAL DEPENDENCIES
-------------------
1. https://github.com/jenssegers/agent - parse UA details
2. http://ipinfo.io/ - get info based on ip address