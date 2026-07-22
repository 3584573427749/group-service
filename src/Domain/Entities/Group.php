<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;
use JsonSerializable;

class Group implements JsonSerializable {
    public function __construct(
        private GroupId $id,
        private GroupLevelId $groupLevelId,
        private string $name,
        private string $description,
        private string $venue,
        private int $active,
        private int $competitive,
        private DateTimeValue $createdAt,
        private ?DateTimeValue $updatedAt,
    ) {
    }

    public function getCreatedAt() : DateTimeValue {
        return $this->createdAt;
    }

    public function getDescription() : string {
        return $this->description;
    }

    public function getId() : GroupId {
        return $this->id;
    }

    public function getGroupLevelId() : GroupLevelId {
        return $this->groupLevelId;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getVenue() : string {
        return $this->venue;
    }

    public function getActive() : int {
        return $this->active;
    }

    public function getCompetitive() : int {
        return $this->competitive;
    }

    public function getUpdatedAt() : ?DateTimeValue {
        return $this->updatedAt;
    }

    public function setGroupLevelId(GroupLevelId $groupLevelId) : void {
        $this->groupLevelId = $groupLevelId;
    }

    public function setDescription(string $description) : void {
        $this->description = $description;
    }

    public function setName(string $name) : void {
        $this->name = $name;
    }

    public function setVenue(string $venue) : Group {
        $this->venue = $venue;

        return $this;
    }

    public function setActive(int $active) : void {
        $this->active = $active;
    }

    public function setCompetitive(int $competitive) : void {
        $this->competitive = $competitive;
    }

    public function setUpdatedAt(?DateTimeValue $updatedAt) : void {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromDBRow(array $row) : self {
        return new self(
            new GroupId($row['id']),
            new GroupLevelId($row['group_level_id']),
            $row['name'],
            $row['description'],
            $row['venue'],
            $row['active'],
            $row['competitive'],
            new DateTimeValue($row['created_at']),
            !empty($row['updated_at']) ? new DateTimeValue($row['updated_at']) : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function asDBRow() : array {
        return [
            'id' => $this->id->toString(),
            'group_level_id' => $this->groupLevelId->toString(),
            'name' => $this->name,
            'description' => $this->description,
            'venue' => $this->venue,
            'active' => $this->active,
            'competitive' => $this->competitive,
            'created_at' => $this->createdAt->toString(),
            'updated_at' => $this->updatedAt ? $this->updatedAt->toString() : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize() : array {
        return [
            'id' => $this->id->toString(),
            'groupLevelId' => $this->groupLevelId->toString(),
            'name' => $this->name,
            'description' => $this->description,
            'venue' => $this->venue,
            'active' => $this->active,
            'competitive' => $this->competitive,
            'createdAt' => $this->createdAt->toString(),
            'updatedAt' => $this->updatedAt ? $this->updatedAt->toString() : null,
        ];
    }
}
