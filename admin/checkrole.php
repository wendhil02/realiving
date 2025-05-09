<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    include '../../connection/connection.php';
    
    function require_role($allowed_roles) {
        if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
            header("Location: ../../loginpage/index.php");
            exit();
        }
    
        // If user's role is not allowed
        if (!in_array($_SESSION['admin_role'], $allowed_roles)) {
            $_SESSION['noti'] = '⚠️ Access Denied: You do not have permission to view this page.';
    
            // Redirect based on actual role to avoid redirect loops
            switch ($_SESSION['admin_role']) {
                case 'superadmin':
                    header("Location: ../superadmin/dashboard.php");
                    break;
                case 'admin5':
                    header("Location: ../admin_mainpage/mainpage.php");
                    break;
                case 'admin4':
                    header("Location: ../admin_mainpage/mainpage.php");
                    break;
                case 'admin3':
                    header("Location: ../admin_mainpage/mainpage.php");
                    break;
                case 'admin2':
                    header("Location: ../admin_mainpage/mainpage.php");
                    break;
                case 'admin1':
                    header("Location: ../admin_mainpage/mainpage.php");
                    break;
                default:
                    header("Location: ../../loginpage/index.php");
                    break;
            }
            exit();
        }
    }
    ?>
    

