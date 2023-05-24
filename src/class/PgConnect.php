<?php
  class PgConnect
  {
    private static $dsn = 'pgsql:dbname=kenshu_backend_php host=database port=5432';
    private static $user = 'postgres';
    private static $password = 'kenshu_backend_php';
    public static PDO | null $client = null;

    public static function getClient() {
      if (is_null(static::$client)) {
        static::$client = new PDO(static::$dsn, static::$user, static::$password, array(PDO::ATTR_PERSISTENT => true));
      }
      return static::$client;
    }
  }
