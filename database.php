<?php

class Database
{
  const LOG_FILE_PATH = '';

  private $pdo;
  private $query;
  private $binds = [];
  private $fetch_pattern = PDO::FETCH_ASSOC;

  function __construct($database, $user, $password) {
    $pdo = new PDO($database, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $this->pdo = $pdo;
  }

  private static function isNullOrEmpty($string) {
    if (is_null($string) || '' == $string) {
      return true;
    }

    return false;
  }

  public function destroy() {
    $this->pdo = null;
  }

  private function commit() {
    $this->pdo->commit();
  }

  private function rollback() {
    $this->pdo->rollback();
  }

  public function setQuery($query) {
    if ( ! self::isNullOrEmpty($query)) {
      $old_query = $this->query;
      $this->query = $old_query . $query;
    }
  }

  private function resetQuery() {
    $this->query = null;
  }

  public function setBind($key, $value, $parameter = null) {
    $this->binds[$key] = [
      'value'     => $value,
      'parameter' => $parameter
    ];
  }

  public function setBindInt($key, $value) {
    $this->setBind($key, $value, PDO::PARAM_INT);
  }

  public function setBindString($key, $value) {
    $this->setBind($key, $value, PDO::PARAM_STR);
  }

  private function resetBind() {
    $this->binds = [];
  }

  public function setFetchPattern($pattern) {
    if ( ! self::isNullOrEmpty($pattern)) {
      $this->fetch_pattern = $pattern;
    }
  }

  private function resetFetchPattern() {
    $this->fetch_pattern = PDO::FETCH_ASSOC;
  }

  private function resetInstance() {
    $this->resetQuery();
    $this->resetBind();
    $this->resetFetchPattern();
  }

  public function select() {
    $pdo = $this->pdo;
    if (self::isNullOrEmpty($pdo)) {
      throw new Exception('PDO instance is not found.', '9999');
    }

    $query = $this->query;
    $binds = $this->binds;
    $fetch_pattern = $this->fetch_pattern;

    try {
      $statement = $pdo->prepare($query);

      foreach ($binds as $key => $bind) {
        $value = $bind['value'];
        $paramter = $bind['parameter'];

        if (self::isNullOrEmpty($parameter)) {
          $statement->bindValue($key, $value);
        }
        else {
          $statement->bindValue($key, $value, $parameter);
        }
      }

      $statement->execute();
      $result = $statement->fetchAll($fetch_pattern);

      $pdo = null;

      $this->resetInstance();

      return $result;
    }
    catch (Exception $exceptions) {
      $error_line = $exceptions->getLine();
      $error_code = $exceptions->getCode();
      $error_message = $exceptions->getMessage();

      $error_log = date('Y-m-d H:i:s') . ' LINE:' . $error_line . ' CODE:' . $error_code . ' MESSAGE:' . $error_message . '¥n';
      error_log($error_log, 3, LOG_FILE_PATH);

      $pdo = null;
      $this->destroy();
      $this->resetInstance();

      return null;
    }
  }

  public function selectOne() {
    $pdo = $this->pdo;
    if (self::isNullOrEmpty($pdo)) {
      throw new Exception('PDO instance is not found.', '9999');
    }

    $query = $this->query;
    $binds = $this->binds;

    try {
      $statement = $pdo->prepare($query);

      foreach ($binds as $key => $bind) {
        $value = $bind['value'];
        $paramter = $bind['parameter'];

        if (self::isNullOrEmpty($parameter)) {
          $statement->bindValue($key, $value);
        }
        else {
          $statement->bindValue($key, $value, $parameter);
        }
      }

      $statement->execute();
      $result = $statement->fetchColumn();

      $pdo = null;

      $this->resetInstance();

      return $result;
    }
    catch (Exception $exceptions) {
      $error_line = $exceptions->getLine();
      $error_code = $exceptions->getCode();
      $error_message = $exceptions->getMessage();

      $error_log = date('Y-m-d H:i:s') . ' LINE:' . $error_line . ' CODE:' . $error_code . ' MESSAGE:' . $error_message . '¥n';
      error_log($error_log, 3, LOG_FILE_PATH);

      $pdo = null;
      $this->destroy();
      $this->resetInstance();

      return null;
    }
  }

  public function update() {
    $pdo = $this->pdo;
    if (self::isNullOrEmpty($pdo)) {
      throw new Exception('PDO instance is not found.', '9999');
    }

    $query = $this->query;
    $binds = $this->binds;

    try {
      $pdo->beginTransaction();

      $statement = $pdo->prepare($query);

      foreach ($binds as $key => $bind) {
        $value = $bind['value'];
        $paramter = $bind['parameter'];

        if (self::isNullOrEmpty($parameter)) {
          $statement->bindValue($key, $value);
        }
        else {
          $statement->bindValue($key, $value, $parameter);
        }
      }

      $statement->execute();
      if (0 == $statement->rowCount()) {
        throw new Exception('update is failed.', '9997');
      }

      $this->commit();

      $pdo = null;

      $this->resetInstance();

      return true;
    }
    catch (Exception $exceptions) {
      $this->rollback();

      $error_line = $exceptions->getLine();
      $error_code = $exceptions->getCode();
      $error_message = $exceptions->getMessage();

      $error_log = date('Y-m-d H:i:s') . ' LINE:' . $error_line . ' CODE:' . $error_code . ' MESSAGE:' . $error_message . '¥n';
      error_log($error_log, 3, LOG_FILE_PATH);

      $pdo = null;
      $this->destroy();
      $this->resetInstance();

      return false;
    }
  }

  public function insert() {
    $pdo = $this->pdo;
    if (self::isNullOrEmpty($pdo)) {
      throw new Exception('PDO instance is not found.', '9999');
    }

    $query = $this->query;
    $binds = $this->binds;

    try {
      $pdo->beginTransaction();

      $statement = $pdo->prepare($query);

      foreach ($binds as $key => $bind) {
        $value = $bind['value'];
        $paramter = $bind['parameter'];

        if (self::isNullOrEmpty($parameter)) {
          $statement->bindValue($key, $value);
        }
        else {
          $statement->bindValue($key, $value, $parameter);
        }
      }

      $statement->execute();
      if (0 == $statement->rowCount()) {
        throw new Exception('insert is failed.', '9998');
      }

      $this->commit();

      $pdo = null;

      $this->resetInstance();

      return true;
    }
    catch (Exception $exceptions) {
      $this->rollback();

      $error_line = $exceptions->getLine();
      $error_code = $exceptions->getCode();
      $error_message = $exceptions->getMessage();

      $error_log = date('Y-m-d H:i:s') . ' LINE:' . $error_line . ' CODE:' . $error_code . ' MESSAGE:' . $error_message . '¥n';
      error_log($error_log, 3, LOG_FILE_PATH);

      $pdo = null;
      $this->destroy();
      $this->resetInstance();

      return false;
    }
  }
}
