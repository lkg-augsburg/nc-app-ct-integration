<?php
namespace OCA\ChurchToolsIntegration\Models;

class CtGroupMember
{
  private int $groupId;
  private string $groupMemberStatus;
  private int $groupTypeRoleId;
  private int $personId;

  public function __construct(int $groupId, string $groupMemberStatus, int $groupTypeRoleId, int $personId)
  {
    $this->groupId = $groupId;
    $this->groupMemberStatus = $groupMemberStatus;
    $this->groupTypeRoleId = $groupTypeRoleId;
    $this->personId = $personId;
  }

  public static function fromJson(array $json)
  {
    $groupId = $json["group"]["domainIdentifier"] ?? null;
    $groupMemberStatus = $json["groupMemberStatus"] ?? null;
    $groupTypeRoleId = $json["groupTypeRoleId"] ?? null;
    $personId = $json["personId"] ?? null;

    if (!isset($groupId, $personId, )) {
      throw new \InvalidArgumentException("Field groupId ($groupId) and/or personId ($personId) is not set.");
    }

    return new CtGroupMember($groupId, $groupMemberStatus, $groupTypeRoleId, $personId);
  }

  /**
   * @return int
   */
  public function getGroupId(): int
  {
    return $this->groupId;
  }

  /**
   * @param int $groupId 
   * @return self
   */
  public function setGroupId(int $groupId): self
  {
    $this->groupId = $groupId;
    return $this;
  }

  /**
   * @return string
   */
  public function getGroupMemberStatus(): string
  {
    return $this->groupMemberStatus;
  }

  /**
   * @param string $groupMemberStatus 
   * @return self
   */
  public function setGroupMemberStatus(string $groupMemberStatus): self
  {
    $this->groupMemberStatus = $groupMemberStatus;
    return $this;
  }

  /**
   * @return int
   */
  public function getGroupTypeRoleId(): int
  {
    return $this->groupTypeRoleId;
  }

  /**
   * @param int $groupTypeRoleId 
   * @return self
   */
  public function setGroupTypeRoleId(int $groupTypeRoleId): self
  {
    $this->groupTypeRoleId = $groupTypeRoleId;
    return $this;
  }

  /**
   * @return int
   */
  public function getPersonId(): int
  {
    return $this->personId;
  }

  /**
   * @param int $personId 
   * @return self
   */
  public function setPersonId(int $personId): self
  {
    $this->personId = $personId;
    return $this;
  }
}