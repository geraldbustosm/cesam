1) Copy the files on xampp\php\ext

2) Add the extensions on xamp\php\php.ini like this:

extension=php_sqlsrv_71_ts_x64.dll
extension=php_pdo_sqlsrv_71_ts_x64.dll

3) Change .env file with the following:

DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=CESAM
DB_USERNAME=SA
DB_PASSWORD=MariaOlga1
