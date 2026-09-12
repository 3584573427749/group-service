<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\DataTransportObjects\GroupLeaderDTO;
use App\Domain\Entities\GroupLeader;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;

interface GroupLeaderRepository {
    public function save(GroupLeader $groupLeader) : void;

    public function get(GroupId $groupId, UserId $userId) : GroupLeader|false;

    /**
     * @return array<GroupLeaderDTO>
     */
    public function getGroupLeaders(GroupId $id) : array;

    /**
     * @return array<GroupLeaderDTO>
     */
    public function getLeaderGroups(UserId $id) : array;

    public function delete(GroupId $groupId, UserId $userId) : void;

    public function deleteByUser(UserId $id) : void;

    public function deleteByGroup(GroupId $id) : void;
}
