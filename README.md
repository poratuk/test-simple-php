# test-simple-php
For one organization small collection form


For install all need:
1. Install PHP > 7.0, composer + MySQL > 5.2
2. Copy .env.example to .env
3. Change in .env yours USPS_USER_ID = "XXXXXXXXX" from https://www.usps.com/business/web-tools-apis/#developers
4. Change mysql connection in .env to yours user/password/
5. Create new database in MySQL server and insert table
  `CREATE TABLE `{DATABASE_NAME}`.`{DATABASE_TABLE}` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `address1` VARCHAR(255), `address2` VARCHAR(255), `city` VARCHAR(255), `state` VARCHAR(255), `zip` INT, PRIMARY KEY (`id`) ); ` 
6. Run `composer install`
7. Run some server. Can use php build in: `php -S localhost:8000` (Documentation)[https://www.php.net/manual/en/features.commandline.webserver.php`]