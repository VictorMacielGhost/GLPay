<?php
    include "core/defines.php";
    switch($_SERVER['REQUEST_URI'])
    {
        case BASE_URL:
        {
            include "index/index.php";
            break;
        }
        default:
        {
            include "errors/404/index.php";
            break;
        }
    }
?>