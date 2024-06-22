<?php

namespace OCA\ChurchToolsIntegration\Models;

class CtGroup
{
  private int $id;
  private string $name;
  private array $tags = [];

  public function __construct(int $id, string $name, array $tags)
  {
    $this->id = $id;
    $this->name = $name;
    $this->setTags($tags);
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

  /**
   * @return \OCA\ChurchToolsIntegration\Models\CtTag[]
   */
  public function getTags(): array
  {
    return $this->tags;
  }

  /**
   * @param \OCA\ChurchToolsIntegration\Models\CtTag[] $tags 
   * @return self
   */
  public function setTags(array $tags): self
  {
    $this->tags = [];
    if (isset($tags)) {
      foreach ($tags as $tag) {
        if (is_array($tag)) {
          $tags[] = CtTag::fromJson($tag);
        } else if ($tag instanceof CtTag) {
          $tags[] = $tag;
        } else {
          throw new \InvalidArgumentException("\$tags is not a valid CtTag array.");
        }
      }
    }
    return $this;
  }

  public static function fromJson($json)
  {
    $id = $json["id"] ?? null;
    $name = $json["name"] ?? null;
    $tags = $json["tags"] ?? null;

    if (!(is_int($id) && is_string($name) && !empty($name))) {
      throw new \InvalidArgumentException("Field id and/or name doesn't exist in the array!");
    }

    return new CtGroup($id, $name, $tags);
  }
}