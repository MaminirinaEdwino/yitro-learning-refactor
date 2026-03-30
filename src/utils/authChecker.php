<?php

function AuthChecker(string $userType): bool
{
    if ($userType == "admin") {
        if (isset($_SESSION["user_id"]) && $_SESSION['user_role'] == "admin") {
            return true;
        }
    } else if ($userType == "apprenant") {
        if (isset($_SESSION["user_id"]) && $_SESSION['user_role'] == "apprenant") {
            return true;
        }
    } else if ($userType == "formateur") {
        if (isset($_SESSION["formateur_id"]) && $_SESSION['user_role'] == "formateur") {
            return true;
        }
    }
    return false;
}
