<?php
namespace OCA\ChurchToolsIntegration\Models;

class CtTag
{
  /**
   * Tag id
   * @var int
   */
  private int $id;
  /**
   * Tag name
   * @var string
   */
  private string $name;

  public function __construct(int $id, string $name)
  {
    $this->id = $id;
    $this->name = $name;
  }

  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * @param int $id 
   * @return self
   */
  public function setId(int $id): self
  {
    $this->id = $id;
    return $this;
  }

  /**
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * @param string $name 
   * @return self
   */
  public function setName(string $name): self
  {
    $this->name = $name;
    return $this;
  }

  public static function fromJson(array $json)
  {
    if (!(isset($json["id"]) && isset($json["name"]))) {
      throw new \InvalidArgumentException("Field id and/or name doesn't exist in the array!");
    }
    return new CtTag($json["id"], $json["name"]);
  }
}