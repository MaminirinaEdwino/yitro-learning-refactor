<?php

function AuthChecker(string $userType): bool{
    if ($userType == "admin") {
        if (isset($_SESSION["user_id"]) && $_SESSION['role'] == "admin") {
            return true;
        }
    }
    return false;
}