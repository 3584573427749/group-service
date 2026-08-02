<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Group;
use App\Domain\Entities\GroupLeader;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;

interface GroupLeaderRepository {
    public function save(GroupLeader $groupLeader) : void;

    public function get(GroupId $groupId, UserId $userId) : GroupLeader|false;

    /**
     * @return array<User>
     */
    public function getUsers(GroupId $id) : array;

    /**
     * @return array<Group>
     */
    public function getGroups(UserId $id) : array;

    public function delete(GroupLeader $groupLeader) : void;

    public function deleteByUser(UserId $id) : void;

    public function deleteByGroup(GroupId $id) : void;
}
