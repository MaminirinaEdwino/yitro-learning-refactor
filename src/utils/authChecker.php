<?php

function AuthChecker(string $userType): bool
{
    if ($userType == "admin") {
        if (isset($_SESSION["user_id"]) && $_SESSION['role'] == "admin") {
            return true;
        }
    } else if ($userType == "apprenant") {
        if (isset($_SESSION["user_id"]) && $_SESSION['role'] == "apprenant") {
            return true;
        }
    } else if ($userType == "formateur") {
        if (isset($_SESSION["formateur_id"]) && $_SESSION['role'] == "formateur") {
            return true;
        }
    }
    return false;
}
