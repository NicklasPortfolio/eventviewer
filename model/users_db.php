<?php

function add_user($username, $password)
{
    global $db;

    $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = 'INSERT INTO users (username, password) VALUES (:username, :password)';
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":password", $encryptedPassword);
    $statement->execute();
    $statement->closeCursor();
}

function fetch_users()
{
    global $db;
    $query = 'SELECT * FROM users ORDER BY user_id';
    $statement = $db->prepare($query);
    $statement->execute();
    $users = $statement->fetchAll();
    $statement->closeCursor();
    return $users;
}

function login_user($username, $password)
{
    global $db;
    $query = 'SELECT * FROM users WHERE username = :username ORDER BY user_id';
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    if ($user && password_verify($password, $user["password"])) {
        return $user;
    } else {
        return false;
    }
}

function delete_user($user_id)
{
    global $db;
    $query = 'DELETE FROM users WHERE user_id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindValue(":user_id", $user_id);
    $statement->execute();
    $statement->closeCursor();
}