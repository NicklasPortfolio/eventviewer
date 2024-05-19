<?php

function add_event($event_name, $event_description, $category_id, $event_location, $event_img, $event_date, $host_ids)
{
    global $db;

    try {
        $db->beginTransaction();

        if ($event_img) {
            $query = 'INSERT INTO events (event_name, event_description, category_id, event_location, event_img, event_date)
            VALUES (:event_name, :event_description, :category_id, :event_location, :event_img, :event_date)';
        } else {
            $query = 'INSERT INTO events (event_name, event_description, category_id, event_location, event_date)
            VALUES (:event_name, :event_description, :category_id, :event_location, :event_date)';
        }

        $statement = $db->prepare($query);
        $statement->bindValue(':event_name', $event_name);
        $statement->bindValue(':event_description', $event_description);
        $statement->bindValue(':category_id', $category_id);
        $statement->bindValue(':event_location', $event_location);
        $statement->bindValue(':event_date', $event_date);
        if ($event_img) {
            $statement->bindValue(':event_img', $event_img);
        }

        $statement->execute();

        $event_id = $db->lastInsertId();

        $query = 'INSERT INTO event_hosts (event_id, host_id) VALUES (:event_id, :host_id)';
        $statement = $db->prepare($query);

        foreach ($host_ids as $host_id) {
            $statement->bindValue(':event_id', $event_id);
            $statement->bindValue(':host_id', $host_id);
            $statement->execute();
        }

        $db->commit();

        $statement->closeCursor();

    } catch (PDOException $e) {
        $db->rollBack();
        exit;
    }
}

function fetch_events($category_id)
{
    global $db;
    if ($category_id) {
        $query = 'SELECT 
                E.event_id,
                E.event_name, 
                C.event_category, 
                E.event_location, 
                E.event_img,
                E.event_date, 
                E.event_description,
                GROUP_CONCAT(CONCAT(H.host_forename, " ", H.host_surname) SEPARATOR ", ") AS event_hosts
            FROM events E
            LEFT JOIN event_categories C ON E.category_id = C.category_id
            LEFT JOIN event_hosts EH ON E.event_id = EH.event_id
            LEFT JOIN hosts H ON EH.host_id = H.host_id
            WHERE E.category_id = :category_id
            GROUP BY E.event_id, E.event_name, C.event_category, E.event_location, E.event_img, E.event_date, E.event_description
            ORDER BY E.event_id';
    } else {
        $query = 'SELECT 
                E.event_id,
                E.event_name, 
                C.event_category AS event_category, 
                E.event_location,
                E.event_img, 
                E.event_date, 
                E.event_description,
                GROUP_CONCAT(CONCAT(H.host_forename, " ", H.host_surname) SEPARATOR ", ") AS event_hosts
            FROM events E
            LEFT JOIN event_categories C ON E.category_id = C.category_id
            LEFT JOIN event_hosts EH ON E.event_id = EH.event_id
            LEFT JOIN hosts H ON EH.host_id = H.host_id
            GROUP BY E.event_id, E.event_name, C.event_category, E.event_location, E.event_img, E.event_date, E.event_description
            ORDER BY E.event_id';
    }

    $statement = $db->prepare($query);
    if ($category_id) {
        $statement->bindValue(':category_id', $category_id);
    }
    $statement->execute();
    $events = $statement->fetchAll();
    $statement->closeCursor();
    return $events;
}

function check_for_matching_events($prompt)
{
    global $db;
    $query = 'SELECT 
                E.event_id,
                E.event_name, 
                C.event_category, 
                E.event_location, 
                E.event_img,
                E.event_date, 
                E.event_description,
                GROUP_CONCAT(CONCAT(H.host_forename, " ", H.host_surname) SEPARATOR ", ") AS event_hosts
            FROM events E
            LEFT JOIN event_categories C ON E.category_id = C.category_id
            LEFT JOIN event_hosts EH ON E.event_id = EH.event_id
            LEFT JOIN hosts H ON EH.host_id = H.host_id
            WHERE E.event_name LIKE "%":prompt"%"
            GROUP BY E.event_id, E.event_name, C.event_category, E.event_location, E.event_img, E.event_date, E.event_description
            ORDER BY E.event_id';

    $statement = $db->prepare($query);
    $statement->bindValue(':prompt', $prompt);
    $statement->execute();
    $events = $statement->fetchAll();
    $statement->closeCursor();
    return $events;
}

function update_event($params)
{
    global $db;

    $event_id = $params["event_id"];
    unset($params["event_id"]);

    $host_ids = isset($params['host_ids']) ? $params['host_ids'] : null;
    unset($params['host_ids']);

    foreach ($params as $column => $value) {
        if (!$value) {
            unset($params[$column]);
        }
    }

    try {
        if ($params) {
            $update_query = "UPDATE events SET ";
            $update_query .= implode(" = ?, ", array_keys($params)) . " = ? ";
            $update_query .= "WHERE event_id = ?";

            $statement = $db->prepare($update_query);
            $values = array_values($params);
            $values[] = $event_id;
            $statement->execute($values);
            $statement->closeCursor();
        }

        if ($host_ids) {
            $query = "DELETE FROM event_hosts WHERE event_id = :event_id";
            $statement = $db->prepare($query);
            $statement->bindValue(":event_id", $event_id);
            $statement->execute();
            $statement->closeCursor();

            $query = 'INSERT INTO event_hosts (event_id, host_id) VALUES (:event_id, :host_id)';
            $statement = $db->prepare($query);
            foreach ($host_ids as $host_id) {
                $statement->bindValue(':event_id', $event_id);
                $statement->bindValue(':host_id', $host_id);
                $statement->execute();
            }
            $statement->closeCursor();
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function delete_event($event_id)
{
    global $db;
    $db->beginTransaction();

    $query = 'DELETE FROM event_hosts WHERE event_id = :event_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':event_id', $event_id);
    $statement->execute();
    $statement->closeCursor();

    $query = 'DELETE FROM events WHERE event_id = :event_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':event_id', $event_id);
    $statement->execute();
    $statement->closeCursor();

    $db->commit();
}