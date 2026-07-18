<?php
/**
 * Authentication & Authorization Middleware
 * Restaurant Management System
 */

namespace Middleware;

/**
 * Reusable middleware for protecting routes.
 * These mirror the global helper functions used by the router
 * (requireAuth, requireAdmin, requireStaff) so routes can be
 * protected via a single, consistent entry point.
 */
class AuthMiddleware
{
    /**
     * Ensure a user is authenticated.
     */
    public function handleAuth(): void
    {
        if (!\auth()) {
            \sessionFlash('error', 'Please login to continue');
            \redirect(\baseUrl('login'));
        }
    }

    /**
     * Ensure the user has admin privileges.
     */
    public function handleAdmin(): void
    {
        if (!\isAdmin()) {
            \sessionFlash('error', 'Access denied. Admin privileges required.');
            \redirect(\baseUrl());
        }
    }

    /**
     * Ensure the user is staff or admin.
     */
    public function handleStaff(): void
    {
        if (!\isStaff()) {
            \sessionFlash('error', 'Access denied.');
            \redirect(\baseUrl());
        }
    }

    /**
     * Block authenticated users from guest-only pages (login/register).
     */
    public function handleGuest(): void
    {
        if (\auth()) {
            \redirect(\baseUrl());
        }
    }
}

/**
 * Global middleware wrappers referenced by the router.
 * The router resolves a string middleware by calling the matching
 * global function, so these keep the existing route definitions valid.
 */
if (!function_exists('requireAuth')) {
    function requireAuth(): void
    {
        (new AuthMiddleware())->handleAuth();
    }
}

if (!function_exists('requireAdmin')) {
    function requireAdmin(): void
    {
        (new AuthMiddleware())->handleAdmin();
    }
}

if (!function_exists('requireStaff')) {
    function requireStaff(): void
    {
        (new AuthMiddleware())->handleStaff();
    }
}

if (!function_exists('requireGuest')) {
    function requireGuest(): void
    {
        (new AuthMiddleware())->handleGuest();
    }
}
