# cesam
Plataforma para el manejo de información en centro de salud mental.

Intalaciones necesarias para windows:

- MSSQL (SQL Server)
- Xampp (Framework de Laravel)
- Microsoft Drivers for PHP for SQL-Server (La versión dependerá de la versión del PHP)
- Luego:

    1) Copiar los archivos on xampp\php\ext

    2) Agregar la extensión a xamp\php\php.ini, por ejemplo:

        extension=php_sqlsrv_XX_ts_x64.dll
        extension=php_pdo_sqlsrv_XX_ts_x64.dll

        (Reemplazar XX por la versión de php, como 71, 72, 73, etc)

    3) Modificar el archivo .env con los siguientes parámetros:

        DB_CONNECTION=sqlsrv
        DB_HOST= YourHost
        DB_PORT= 1433 (Usual de mssql)
        DB_DATABASE= YourDB
        DB_USERNAME= YourUser
        DB_PASSWORD= YourPass
    
    - Es posible que no tenga el archivo .env, para este caso debe copiar el archivo .env-example cambiar su nombre a .env y modificarlo

Para linux (indicaciones generales):

- Base de Datos: mssql-server, mssql-tools, unixODBC-devel
- Otros Softwares: php 7.2, Laravel (framework), composer
- Microsoft Drivers for PHP for SQL-Server

    - Es necesario permisos 755 en la carpeta de laravel y 777 en la carpeta laravel/storage
