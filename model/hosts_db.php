<?php

function add_host($host_forename, $host_surname)
{
    global $db;
    $query = 'INSERT INTO hosts (host_forename, host_surname) VALUES (:host_forename, :host_surname)';
    $statement = $db->prepare($query);
    $statement->bindValue(":host_forename", $host_forename);
    $statement->bindValue(":host_surname", $host_surname);
    $statement->execute();
    $statement->closeCursor();
}

function fetch_hosts($host_id)
{
    global $db;
    if ($host_id) {
        $query = 'SELECT 
                    H.host_id,
                    CONCAT(H.host_forename, " ", H.host_surname) AS host_name
                FROM hosts H 
                WHERE H.host_id = :host_id
                ORDER BY H.host_id';
    } else {
        $query = 'SELECT 
                    H.host_id,
                    CONCAT(H.host_forename, " ", H.host_surname) AS host_name
                FROM hosts H 
                ORDER BY H.host_id';
    }
    $statement = $db->prepare($query);
    if ($host_id) {
        $statement->bindValue(":host_id", $host_id);
    }
    $statement->execute();
    $host_names = $statement->fetchAll();
    $statement->closeCursor();
    return $host_names;
}

function delete_host($host_id)
{
    global $db;
    $transaction = false;

    $query = 'SELECT * FROM event_hosts WHERE host_id = :host_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':host_id', $host_id);
    $statement->execute();

    if (!empty($statement->fetchAll()) || $statement->fetch()) {
        $transaction = true;
        $db->beginTransaction();

        $query = 'DELETE FROM event_hosts WHERE host_id = :host_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':host_id', $host_id);
        $statement->execute();

        $query = 'DELETE FROM hosts WHERE host_id = :host_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':host_id', $host_id);
        $statement->execute();

        $db->commit();
    } else {
        $query = 'DELETE FROM hosts WHERE host_id = :host_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':host_id', $host_id);
        $statement->execute();
    }

    $statement->closeCursor();
}