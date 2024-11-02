<?php
namespace OCA\ChurchToolsIntegration\Service;

use OCA\ChurchToolsIntegration\Models\CtGroup;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IGroup;
use OCP\IGroupManager;
use OCP\IUserManager;

class GroupSyncService
{
  private GroupService $groupService;
  private CtRestClient $ctClient;

  public function __construct(
    GroupService $groupService,
    CtRestClient $ctClient,
  ) {

    $this->$groupService = $groupService;
    $this->$ctClient = $ctClient;
  }

  // public function syncGroups()
  // {
  //   $results = array_reduce(
  //     $this->groupService->listGroups(),
  //     function (array $carry, IGroup $group) {
  //       if ($group->getGID() != "admin") {
  //         $carry[$group->getGID()] = "INIT";
  //       }
  //       return $carry;
  //     },
  //     []
  //   );

  //   $ctGroups = array_map(fn($tag) => CtGroup::fromJson($tag), $this->ctClient->fetchSyncGroups());
  //   foreach ($ctGroups as $group) {
  //     $gid = "ct-" . $group->getId() . "_" . preg_replace('/\s+/im', '-', strtolower($group->getName()));
  //     if (!isset($results[$gid])) {
  //       $gname = $group->getName();
  //       $groupCreated = $this->groupService->createGroup($gid, $gname);

  //       $results[$gid] = $groupCreated ? "CREATE" : "NO_CREATE";
  //     } else {
  //       $results[$gid] = "EXIST";
  //     }
  //   }

  //   foreach ($results as $gid => $state) {
  //     if ($state == "INIT") {
  //       $this->groupService->deleteGroup($gid);
  //       $results[$gid] = "DELETE";
  //     }
  //   }

  //   return new JSONResponse($results);
  // }

  // public function syncGroupMembers(string $gid)
  // {
  //   $group = $this->groupService->getGroup($gid);

  //   if ($group == null) {
  //     throw new \InvalidArgumentException("$gid is not an existing group!");
  //   }

  //   $ctGid = preg_replace("/^ct-(\d+)_.*/", "$1", $gid);

  //   return $ctGid;

  //   // return $this->ctClient->fetchGroupMembers("$ctGid");
  // }
}