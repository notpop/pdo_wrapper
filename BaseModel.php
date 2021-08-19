<?php

class BaseModel
{
  const DATA_SOURCE_NAME = '';
  const USER = '';
  const PASSWORD = '';

  protected $database;

  function __construct() {
    $database = new Database(DATA_SOURCE_NAME, USER, PASSWORD);

    $this->database = $database;
  }

  public function destroy() {
    $this->database->destroy();
  }
}
