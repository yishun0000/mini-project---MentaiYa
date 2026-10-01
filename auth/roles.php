<?php
session_start();

function isAdmin()
{
    if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
        if ($_SESSION['user']['role'] === 'Admin') {
            return true;
        }
    }

    return false;
}

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

function isCustomer()
{
    if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
        if ($_SESSION['user']['role'] === 'Customer') {
            return true;
        }
    }

    return false;
}

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