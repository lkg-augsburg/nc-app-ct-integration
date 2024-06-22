<?php
namespace OCA\ChurchToolsIntegration\Models;

class CtUser
{
  private $id;
  private $email;

  public function __construct($id, $email)
  {
    $this->id = $id;
    $this->email = $email;
  }

  public static function fromJson($json)
  {
    $id = $json["id"] ?? null;
    $email = $json["email"] ?? null;


    if (!isset($id, $email)) {
      throw new \InvalidArgumentException("Field id and/or email is not set");
    }
    return new CtUser($id, $email);
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id 
   * @return self
   */
  public function setId($id): self
  {
    $this->id = $id;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param mixed $email 
   * @return self
   */
  public function setEmail($email): self
  {
    $this->email = $email;
    return $this;
  }
}