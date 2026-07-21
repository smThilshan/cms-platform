<?php

namespace App\Enums;

enum Privilege: string
{
    // Pages
    case PagesList   = 'pages.list';
    case PagesCreate = 'pages.create';
    case PagesEdit   = 'pages.edit';
    case PagesDelete = 'pages.delete';

    // Menu
    case MenuList    = 'menu.list';
    case MenuCreate  = 'menu.create';
    case MenuEdit    = 'menu.edit';
    case MenuDelete  = 'menu.delete';
    case MenuReorder = 'menu.reorder';

    // Roles
    case RolesList   = 'roles.list';
    case RolesCreate = 'roles.create';
    case RolesEdit   = 'roles.edit';
    case RolesDelete = 'roles.delete';

    // Privileges
    case PrivilegesList   = 'privileges.list';
    case PrivilegesCreate = 'privileges.create';
    case PrivilegesEdit   = 'privileges.edit';
    case PrivilegesDelete = 'privileges.delete';

    public function description(): string
    {
        return match($this) {
            self::PagesList      => 'List pages',
            self::PagesCreate    => 'Create pages',
            self::PagesEdit      => 'Edit pages',
            self::PagesDelete    => 'Delete pages',
            self::MenuList       => 'List menu items',
            self::MenuCreate     => 'Create menu items',
            self::MenuEdit       => 'Edit menu items',
            self::MenuDelete     => 'Delete menu items',
            self::MenuReorder    => 'Reorder menu items',
            self::RolesList      => 'List roles',
            self::RolesCreate    => 'Create roles',
            self::RolesEdit      => 'Edit roles',
            self::RolesDelete    => 'Delete roles',
            self::PrivilegesList   => 'List privileges',
            self::PrivilegesCreate => 'Create privileges',
            self::PrivilegesEdit   => 'Edit privileges',
            self::PrivilegesDelete => 'Delete privileges',
        };
    }

    public static function moderatorPrivileges(): array
    {
        return [
            self::PagesList,
            self::PagesCreate,
            self::PagesEdit,
            self::MenuList,
            self::MenuCreate,
            self::MenuEdit,
            self::MenuReorder,
        ];
    }
}
