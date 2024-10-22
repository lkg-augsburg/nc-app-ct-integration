<?php

namespace OCA\ChurchToolsIntegration\Controller;

use OCA\ChurchToolsIntegration\Models\CtGroup;
use OCA\ChurchToolsIntegration\Service\CtRestClient;
use OCA\ChurchToolsIntegration\Service\GroupService;
use OCA\ChurchToolsIntegration\Models\CtGroupMember;
use OCA\ChurchToolsIntegration\Models\CtUser;
use OCA\ChurchToolsIntegration\Service\AppConfigService;
use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IGroup;
use OCP\IUser;

class NcController extends Controller
{
  private GroupService $groupService;
  private AppConfigService $appConfigService;
  private CtRestClient $ctClient;

  public function __construct(
    $AppName,
    IRequest $request,
    GroupService $groupService,
    CtRestClient $ctClient,
    AppConfigService $appConfigService,
  ) {
    parent::__construct($AppName, $request);
    $this->groupService = $groupService;
    $this->ctClient = $ctClient;
    $this->appConfigService = $appConfigService;
  }

  private function _buildResponse($status = '', $message = '', $data = null){
    return new JSONResponse([
      'status' => $status,
      'data' => $data,
      'message' => $message
    ]);
  }

  public function saveConfiguration($url, $token){
    // $urlUpdated = $this->appConfigService->setCtUrl($url);
    // $this->appConfigService->setCtUserToken($token);

    return $this->_buildResponse(
      status: 'ok',
      data: [
        'url' => $this->appConfigService->setCtUrl($url),
        'token' => $this->appConfigService->setCtUserToken($token),
      ]
    );
  }


  // public function fetchExistingGroups()
  // {
  //   return new JSONResponse($this->groupService->listGroups());
  // }

  // public function syncGroups()
  // {
  //   // Load NC groups
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
    
  //   // Load CT groups
  //   $ctSyncGroups = $this->ctClient->fetchSyncGroups();
  //   $ctGroups = array_map(fn($tag) => CtGroup::fromJson($tag), $ctSyncGroups);
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

  //   // Delete groups which aren't necessary anymore
  //   foreach ($results as $gid => $state) {
  //     if ($state == "INIT") {
  //       $this->groupService->deleteGroup($gid);
  //       $results[$gid] = "DELETE";
  //     } else {
  //       $results[$gid] = $this->syncGroupMembers($gid)->getData();
  //     }
  //   }

  //   return new JSONResponse($results);
  // }

  // public function syncGroupMembers(string $gid)
  // {
  //   // Get group from NC database
  //   $group = $this->groupService->getGroup($gid);

  //   if ($group == null) {
  //     throw new \InvalidArgumentException("$gid is not an existing group!");
  //   }

  //   // Extract CT group ID
  //   $ctGid = preg_replace("/^ct-(\d+)_.*/", "$1", $gid);

  //   // Extract CT user ids of group members
  //   $ctGroupMemberIds = array_map(
  //     function (CtGroupMember $member) {
  //       return $member->getPersonId();
  //     },
  //     $this->ctClient->fetchGroupMembers($ctGid)
  //   );

  //   // Fetch CT user objects for group members
  //   $ctUserMap = array_reduce(
  //     $this->ctClient->fetchUsers($ctGroupMemberIds),
  //     function (array $carry, CtUser $member) {
  //       $carry[$member->getId()] = $member;
  //       return $carry;
  //     },
  //     []
  //   );

  //   // Map NC users to identify changes
  //   $ncGroupMemberState = array_reduce(
  //     $group->getUsers(),
  //     function ($carry, IUser $user) {
  //       $carry[$user->getUid()] = [
  //         "state" => "DELETE",
  //         "user" => ["email" => $user->getUID()],
  //       ];
  //       return $carry;
  //     },
  //     []
  //   );

  //   // Add CT user to NC groups
  //   foreach ($ctGroupMemberIds as $ctUserId) {
  //     $email = $ctUserMap[$ctUserId]->getEmail();
  //     $this->groupService->addUserToGroup($email, $gid);

  //     if (isset($ncGroupMemberState[$email])) {
  //       $ncGroupMemberState[$email]["state"] = "EXIST";
  //       $ncGroupMemberState[$email]["user"]["id"] = $ctUserId;
  //     } else {
  //       $ncGroupMemberState[$email] = [
  //         "state" => "CREATE",
  //         "user" => [
  //           "email" => $email,
  //           "id" => $ctUserId,
  //         ],
  //       ];
  //     }
  //   }

  //   // Remove NC users from NC group which aren't a member anymore
  //   foreach ($ncGroupMemberState as $ncGroupMemberId => ["state" => $state]) {
  //     if ($state == "DELETE") {
  //       $this->groupService->removeUserFromGroup($ncGroupMemberId, $gid);
  //     }
  //   }

  //   return new JSONResponse(array_map(function (array $groupMember) {
  //     return [
  //       "mail" => $groupMember["user"]["email"],
  //       "id" => $groupMember["user"]["id"],
  //       "state" => $groupMember["state"],
  //     ];
  //   }, $ncGroupMemberState));
  // }
}