<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;
use JsonSerializable;

class GroupLevel implements JsonSerializable {
    public function __construct(
        private GroupLevelId $id,
        private string $name,
        private string $description,
        private int $sortOrder,
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

    public function getId() : GroupLevelId {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getSortOrder() : int {
        return $this->sortOrder;
    }

    public function getUpdatedAt() : ?DateTimeValue {
        return $this->updatedAt;
    }

    public function setDescription(string $description) : void {
        $this->description = $description;
    }

    public function setName(string $name) : void {
        $this->name = $name;
    }

    public function setSortOrder(int $sortOrder) : void {
        $this->sortOrder = $sortOrder;
    }

    public function setUpdatedAt(?DateTimeValue $updatedAt) : void {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromDBRow(array $row) : self {
        return new self(
            new GroupLevelId($row['id']),
            $row['name'],
            $row['description'],
            $row['sort_order'],
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
            'name' => $this->name,
            'description' => $this->description,
            'sort_order' => $this->sortOrder,
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
            'name' => $this->name,
            'description' => $this->description,
            'sortOrder' => $this->sortOrder,
            'createdAt' => $this->createdAt->toString(),
            'updatedAt' => $this->updatedAt ? $this->updatedAt->toString() : null,
        ];
    }
}
