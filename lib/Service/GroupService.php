<?php
namespace OCA\ChurchToolsIntegration\Service;

use OCP\IGroupManager;
use OCP\IUserManager;

class GroupService
{
  private $groupManager;
  private $userManager;

  public function __construct(IGroupManager $groupManager, IUserManager $userManager)
  {
    $this->groupManager = $groupManager;
    $this->userManager = $userManager;
  }

  public function getGroup(string $gid)
  {
    return $this->groupManager->get($gid);
  }

  public function listGroups()
  {
    return $this->groupManager->search('');
  }

  public function createGroup(string $gid, string $groupName = null): bool
  {
    $group = $this->groupManager->createGroup($gid);
    if (isset($groupName)) {
      $group->setDisplayName($groupName);
    }

    return $group != null;
  }

  public function deleteGroup(string $groupName): bool
  {
    $group = $this->groupManager->get($groupName);
    if ($group) {
      return $group->delete();
    }
    return false;
  }
  public function addUserToGroup(string $username, string $groupName): bool
  {
    $group = $this->groupManager->get($groupName);
    $user = $this->userManager->get($username);
    if ($group && $user) {
      $group->addUser($user);
      return true;
    }
    return false;
  }

  public function removeUserFromGroup(string $username, string $groupName): bool
  {
    $group = $this->groupManager->get($groupName);
    $user = $this->userManager->get($username);
    if ($group && $user) {
      $group->removeUser($user);
      return true;
    }
    return false;
  }
}
