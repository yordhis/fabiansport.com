<?php

    //Archivo de configuracion de entorno

   
    // define('DATABASE', [
    //     'driver'    => 'mysql',
    //     'host'      => 'localhost',
    //     'database'  => 'fabiansp_fs',
    //     'username'  => 'fabiansport',
    //     'password'  => 'adoracionviva',
    //     'charset'   => 'utf8',
    //     'collation' => 'utf8_unicode_ci',
    //     'prefix'    => '',
    // ]);
    
    //localhost local debug

    // define('DATABASE', [
    //     'driver'    => 'mysql',
    //     'host'      => 'localhost',
    //     'database'  => 'fs',
    //     'username'  => 'root',
    //     'password'  => '',
    //     'charset'   => 'utf8',
    //     'collation' => 'utf8_unicode_ci',
    //     'prefix'    => '',
    // ]);

    define('DATABASE', [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'fabiansp_fs',
        'username'  => 'fabiansport',
        'password'  => 'Adoracionviva.3467',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);
    //id8472058_tienda

    //Errores de la base de datos
    define('ERRORES_BASEDATOS',[
        "00000" => "Resultado exitoso",
        "0" => "Resultado exitoso",
        "1" => "Error de SQL o falta de base de datos",
        "2" => "Un error de lógica interna en SQLite",
        "3" => "Permiso de acceso denegado",
        "4" => "La rutina de devolución de llamada solicitó un aborto",
        "5" => "Resultado exitoso",
        "6" => "Resultado exitoso",
        "7" => "Resultado exitoso",
        "8" => "Resultado exitoso",
        "9" => "Resultado exitoso",
        "10" => "Resultado exitoso",
        "11" => "Resultado exitoso",
        "12" => "Resultado exitoso",
        "13" => "Resultado exitoso",
        "14" => "Resultado exitoso",
        "15" => "Resultado exitoso",
        "16" => "Resultado exitoso",
        "17" => "Resultado exitoso",
        "18" => "Resultado exitoso",
        "19" => "Resultado exitoso",
        "21" => "Resultado exitoso",
        "22" => "Resultado exitoso",
        "23" => "Autorización denegada",
        "100" => "Resultado exitoso",
        "23000" => "Entrada duplicada",
        "HY093" => "Número de parámetro no válido",
    ]);

    #define SQLITE_OK           0   /* Successful result */
#define SQLITE_ERROR        1   /* SQL error or missing database */
#define SQLITE_INTERNAL     2   /* An internal logic error in SQLite */
#define SQLITE_PERM         3   /* Access permission denied */
#define SQLITE_ABORT        4   /* Callback routine requested an abort */
#define SQLITE_BUSY         5   /* The database file is locked */
#define SQLITE_LOCKED       6   /* A table in the database is locked */
#define SQLITE_NOMEM        7   /* A malloc() failed */
#define SQLITE_READONLY     8   /* Attempt to write a readonly database */
#define SQLITE_INTERRUPT    9   /* Operation terminated by sqlite_interrupt() */
#define SQLITE_IOERR       10   /* Some kind of disk I/O error occurred */
#define SQLITE_CORRUPT     11   /* The database disk image is malformed */
#define SQLITE_NOTFOUND    12   /* (Internal Only) Table or record not found */
#define SQLITE_FULL        13   /* Insertion failed because database is full */
#define SQLITE_CANTOPEN    14   /* Unable to open the database file */
#define SQLITE_PROTOCOL    15   /* Database lock protocol error */
#define SQLITE_EMPTY       16   /* (Internal Only) Database table is empty */
#define SQLITE_SCHEMA      17   /* The database schema changed */
#define SQLITE_TOOBIG      18   /* Too much data for one row of a table */
#define SQLITE_CONSTRAINT  19   /* Abort due to contraint violation */
#define SQLITE_MISMATCH    20   /* Data type mismatch */
#define SQLITE_MISUSE      21   /* Library used incorrectly */
#define SQLITE_NOLFS       22   /* Uses OS features not supported on host */
#define SQLITE_AUTH        23   /* Authorization denied */
#define SQLITE_ROW         100  /* sqlite_step() has another row ready */
#define SQLITE_DONE        101  /* sqlite_step() has finished executing */
    