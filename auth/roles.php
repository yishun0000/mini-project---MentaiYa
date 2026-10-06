<?php
/* =========================================
   AUTHENTICATION & ROLE VALIDATION HELPERS
   ========================================= */

session_start();

/* 
 * Check if the currently logged-in user has 'Admin' privileges.
 * Returns true if authorized, false otherwise.
 */
function isAdmin()
{
    if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
        if ($_SESSION['user']['role'] === 'Admin') {
            return true;
        }
    }

    return false;
}

/* 
 * Check if the user has 'Staff' or 'Admin' privileges.
 * Allows both Staff and Admin users to access kitchen/staff features.
 */
function isStaff()
{
    if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
        if (
            $_SESSION['user']['role'] === 'Staff' ||
            $_SESSION['user']['role'] === 'Admin'
        ) {
            return true;
        }
    }

    return false;
}

/* 
 * Check if the currently logged-in user is a 'Customer'.
 */
function isCustomer()
{
    if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
        if ($_SESSION['user']['role'] === 'Customer') {
            return true;
        }
    }

    return false;
}

/* 
 * Check if any valid user session exists regardless of role.
 */
function isUser()
{
    if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
        if (
            $_SESSION['user']['role'] === 'Admin' ||
            $_SESSION['user']['role'] === 'Staff' ||
            $_SESSION['user']['role'] === 'Customer'
        ) {
            return true;
        }
    }

    return false;
}
?>