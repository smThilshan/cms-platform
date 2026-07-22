<?php

use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PrivilegeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Public\SiteController;
use Illuminate\Support\Facades\Route;

// ── Auth ────────────────────────────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
});

// ── Public (no auth) ────────────────────────────────────────────────────────
Route::get('/menu',          [SiteController::class, 'menu']);
Route::get('/pages/{slug}',  [SiteController::class, 'page']);

// ── Admin (auth + per-route privilege check) ─────────────────────────────────
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

    // Pages
    Route::get('/pages',         [PageController::class, 'index'])->middleware('privilege:pages.list');
    Route::post('/pages',        [PageController::class, 'store'])->middleware('privilege:pages.create');
    Route::get('/pages/{page}',  [PageController::class, 'show'])->middleware('privilege:pages.list');
    Route::post('/pages/{page}', [PageController::class, 'update'])->middleware('privilege:pages.edit');
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->middleware('privilege:pages.delete');

    // Menu items — reorder MUST come before {menu_item} wildcard
    Route::get('/menu-items',              [MenuItemController::class, 'index'])->middleware('privilege:menu.list');
    Route::post('/menu-items',             [MenuItemController::class, 'store'])->middleware('privilege:menu.create');
    Route::post('/menu-items/reorder',     [MenuItemController::class, 'reorder'])->middleware('privilege:menu.reorder');
    Route::post('/menu-items/{menu_item}', [MenuItemController::class, 'update'])->middleware('privilege:menu.edit');
    Route::delete('/menu-items/{menu_item}', [MenuItemController::class, 'destroy'])->middleware('privilege:menu.delete');

    // Roles
    Route::get('/roles',          [RoleController::class, 'index'])->middleware('privilege:roles.list');
    Route::post('/roles',         [RoleController::class, 'store'])->middleware('privilege:roles.create');
    Route::get('/roles/{role}',   [RoleController::class, 'show'])->middleware('privilege:roles.list');
    Route::post('/roles/{role}',  [RoleController::class, 'update'])->middleware('privilege:roles.edit');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('privilege:roles.delete');

    // Privileges
    Route::get('/privileges',               [PrivilegeController::class, 'index'])->middleware('privilege:privileges.list');
    Route::post('/privileges',              [PrivilegeController::class, 'store'])->middleware('privilege:privileges.create');
    Route::get('/privileges/{privilege}',   [PrivilegeController::class, 'show'])->middleware('privilege:privileges.list');
    Route::post('/privileges/{privilege}',  [PrivilegeController::class, 'update'])->middleware('privilege:privileges.edit');
    Route::delete('/privileges/{privilege}', [PrivilegeController::class, 'destroy'])->middleware('privilege:privileges.delete');

    // Users
    Route::get('/users',          [UserController::class, 'index'])->middleware('privilege:users.list');
    Route::post('/users',         [UserController::class, 'store'])->middleware('privilege:users.create');
    Route::get('/users/{user}',   [UserController::class, 'show'])->middleware('privilege:users.list');
    Route::post('/users/{user}',  [UserController::class, 'update'])->middleware('privilege:users.edit');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('privilege:users.delete');
});
