<?php

namespace App\Core\Domain\Entities;

class RoleEntity
{
    public ?int $id;
    public ?int $shop_id;
    public string $title;
    public ?string $name;
    public ?int $parent_id;
    public ?int $order;
    public ?string $description;
    public ?string $guard_name;
    public ?int $type_information;
    public ?string $shop_access;
    public ?string $shop_expect;
    public ?string $created_at;
    public ?string $updated_at;
    public ?string $deleted_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->shop_id = $data['shop_id'] ?? null;
        $this->title = $data['title'] ?? 'admin';
        $this->name = $data['name'] ?? null;
        $this->parent_id = $data['parent_id'] ?? 0;
        $this->order = $data['order'] ?? 0;
        $this->description = $data['description'] ?? null;
        $this->guard_name = $data['guard_name'] ?? "web";
        $this->type_information = $data['type_information'] ?? null;
        $this->shop_access = $data['shop_access'] ?? null;
        $this->shop_expect = $data['shop_expect'] ?? null;
        $this->shop_expect = $data['shop_expect'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
        $this->deleted_at = $data['deleted_at'] ?? null;
    }
}
