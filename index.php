<?php
require 'model/config/database.php';
require 'model/events_db.php';
require 'model/categories_db.php';
require 'model/hosts_db.php';
require 'model/users_db.php';
global $db;

session_start();

$event_id = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
$event_name = filter_input(INPUT_POST, 'event_name', FILTER_SANITIZE_STRING);
$event_description = filter_input(INPUT_POST, 'event_description', FILTER_SANITIZE_STRING);
$event_location = filter_input(INPUT_POST, 'event_location', FILTER_SANITIZE_STRING);
$event_img = filter_input(INPUT_POST, 'event_img', FILTER_SANITIZE_STRING);
$event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING);

$category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
$category_name = filter_input(INPUT_POST, "category_name", FILTER_SANITIZE_STRING);

$host_id = filter_input(INPUT_POST, 'host_id', FILTER_VALIDATE_INT);
$host_forename = filter_input(INPUT_POST, "host_forename", FILTER_SANITIZE_STRING);
$host_surname = filter_input(INPUT_POST, "host_surname", FILTER_SANITIZE_STRING);

$host_ids_raw = filter_input(INPUT_POST, 'host_ids', FILTER_SANITIZE_STRING);
if ($host_ids_raw) {
    $host_ids = array_filter(explode(',', $host_ids_raw), function ($id) {
        return filter_var($id, FILTER_VALIDATE_INT) !== false;
    });
}

$user_id = filter_input(INPUT_POST, "user_id", FILTER_VALIDATE_INT);
$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
$login_error = filter_input(INPUT_GET, 'login_error', FILTER_SANITIZE_STRING);

$success_message = filter_input(INPUT_GET, 'success_message', FILTER_SANITIZE_STRING);

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if (!$action) {
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    if (!$action) {
        $action = 'list_events';
    }
}

switch ($action) {
    case "add_event_page":
        $hosts = fetch_hosts($host_id);
        $categories = fetch_categories();
        include 'view/events/add_event.php';
        break;

    case "add_event":
        if ($event_name && $event_description && $category_id && $event_location && $event_date && !empty($host_ids)) {
            add_event($event_name, $event_description, $category_id, $event_location, $event_img, $event_date, $host_ids);
            header("Location: .?category_id=$category_id");
        } else {
            $error = "Invalid event data. Check all fields and try again.";
            include "view/shared/error.php";
            exit;
        }
        break;

    case 'search_event':
        $search_prompt = filter_input(INPUT_POST, 'search_prompt', FILTER_SANITIZE_STRING);
        $events = check_for_matching_events($search_prompt);
        include 'view/events/list_events.php';
        break;

    case "edit_event_page":
        $hosts = fetch_hosts($host_id);
        $categories = fetch_categories();
        include 'view/events/edit_event.php';
        break;

    case "edit_event":
        try {
            $params = array(
                "event_id" => $event_id,
                "event_name" => $event_name,
                "event_description" => $event_description,
                "category_id" => $category_id,
                "event_location" => $event_location,
                "event_img" => $event_img,
                "event_date" => $event_date,
                "host_ids" => $host_ids
            );
            update_event($params);
            header("Location: ./?category_id=$category_id");
        } catch (PDOException $e) {
            $error = "An error occured while updating the event: " . $e->getMessage();
            include "view/shared/error.php";
            exit;
        }
        break;

    case "delete_event":
        if ($event_id) {
            try {
                delete_event($event_id);
                header("Location: ./?category_id=$category_id");
            } catch (PDOException $e) {
                $db->rollBack();
                $error = "An error occured while deleting the event: " . $e->getMessage();
                include "view/shared/error.php";
                exit;
            }
        } else {
            $error = "Missing or incorrect event id.";
            include "view/shared/error.php";
            exit;
        }
        break;

    case "add_category_page":
        $add_page = "categories";
        include 'view/add.php';
        break;

    case "add_category":
        add_category($category_name);
        header("Location: .?action=list_categories");
        break;

    case "list_categories":
        $categories = fetch_categories();
        include 'view/list.php';
        break;

    case "delete_category":
        if ($category_id) {
            try {
                delete_category($category_id);
            } catch (PDOException $e) {
                $db->rollBack();
                $error = "An error occured while deleting the category: " . $e->getMessage();
                include "view/shared/error.php";
                exit;
            }
            header("Location: .?action=list_categories");
        } else {
            $error = "Missing or incorrect category id.";
            include "view/shared/error.php";
            exit;
        }
        break;

    case "add_host_page":
        $add_page = "hosts";
        include 'view/add.php';
        break;

    case "add_host":
        add_host($host_forename, $host_surname);
        header("Location: .?action=list_hosts");
        break;

    case "list_hosts":
        $hosts = fetch_hosts($host_id);
        include 'view/list.php';
        break;

    case "delete_host":
        if ($host_id) {
            try {
                delete_host($host_id);
            } catch (PDOException $e) {
                if ($transaction) {
                    $db->rollBack();
                }
                $error = "An error occured while deleting the host: " . $e->getMessage();
                include "view/shared/error.php";
                exit;
            }
            header("Location: .?action=list_hosts");
        } else {
            $error = "Missing or incorrect host id.";
            include "view/shared/error.php";
            exit;
        }
        break;

    case "add_user_page":
        $add_page = "users";
        include 'view/add.php';
        break;

    case "add_user":
        add_user($username, $password);
        header("Location: .?action=list_users");
        break;

    case "list_users":
        $users = fetch_users();
        include 'view/list.php';
        break;

    case "delete_user":
        if ($user_id) {
            try {
                delete_user($user_id);
            } catch (PDOException $e) {
                $error = "An error occured while attempting to delete the user " . $e->getMessage();
                include "view/shared/error.php";
            }
            header("Location: .?action=list_users");
        }
        break;

    case "login_page":
        include 'view/login.php';
        break;

    case "login":
        if ($username && $password) {
            try {
                $result = login_user($username, $password);
                if ($result) {
                    $_SESSION["admin"] = true;
                    header("Location: .?action=list_events");
                } else {
                    $login_error = "Incorrect username or password.";
                    header("Location: .?action=login_page&login_error=$login_error");
                }
            } catch (Exception $e) {
                $error = "An error occured when fetching user data " . $e->getMessage();
                include "view/shared/error.php";
            }
        } else {
            $error = "Invalid user data, check all fields and try again.";
            include "view/shared/error.php";
        }
        break;

    case "logout":
        session_destroy();
        header("Location: .?action=list_events");
        break;

    case "contact_page":
        include 'view/contact.php';
        break;

    case "contact":
        $success_message = "Successfully sent message, we'll be in touch shortly!";
        header("Location: .?action=contact_page&success_message=$success_message");
        break;

    default:
        $event_category = fetch_category_name($category_id);
        $categories = fetch_categories();
        $events = fetch_events($category_id);
        include 'view/events/list_events.php';
        break;
}