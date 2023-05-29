<?php
  class PgConnect
  {
    public static PDO | null $client = null;

    public static function getClient() {
      if (is_null(static::$client)) {
        $db_name = getenv("DB_NAME");
        $host = getenv("DB_HOST");
        $port = getenv("DB_PORT");
        $user = getenv("DB_USER");
        $password = getenv("DB_PASSWORD");
        $dsn = "pgsql:dbname=".$db_name." "."host=".$host." "."port=".$port;

        static::$client = new PDO($dsn, $user, $password, array(PDO::ATTR_PERSISTENT => true));
      }
      return static::$client;
    }
  }
