<?php

function add_event($event_name, $event_description, $category_id, $event_location, $event_img, $event_date, $host_ids)
{
    global $db;

    try {
        // Starta en transaktion
        $db->beginTransaction();

        // Bestäm vilken SQL-fråga vi ska köra beroende på om det finns en bild eller inte
        if ($event_img) {
            $query = 'INSERT INTO events (event_name, event_description, category_id, event_location, event_img, event_date)
            VALUES (:event_name, :event_description, :category_id, :event_location, :event_img, :event_date)';
        } else {
            $query = 'INSERT INTO events (event_name, event_description, category_id, event_location, event_date)
            VALUES (:event_name, :event_description, :category_id, :event_location, :event_date)';
        }

        $statement = $db->prepare($query);
        // Binda värden till parametrar
        $statement->bindValue(':event_name', $event_name);
        $statement->bindValue(':event_description', $event_description);
        $statement->bindValue(':category_id', $category_id);
        $statement->bindValue(':event_location', $event_location);
        $statement->bindValue(':event_date', $event_date);
        if ($event_img) {
            $statement->bindValue(':event_img', $event_img);
        }

        // Exekvera frågan
        $statement->execute();

        // Hämta det senaste insatta event_id
        $event_id = $db->lastInsertId();

        // Lägg till värdar för eventet
        $query = 'INSERT INTO event_hosts (event_id, host_id) VALUES (:event_id, :host_id)';
        $statement = $db->prepare($query);

        foreach ($host_ids as $host_id) {
            $statement->bindValue(':event_id', $event_id);
            $statement->bindValue(':host_id', $host_id);
            $statement->execute();
        }

        // Avsluta transaktionen
        $db->commit();
        $statement->closeCursor();

    } catch (PDOException $e) {
        // Om något går fel, rulla tillbaka transaktionen
        $db->rollBack();
        exit;
    }
}

function fetch_events($category_id)
{
    global $db;
    if ($category_id) {
        // SQL-fråga om kategori_id är satt
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
        // SQL-fråga om kategori_id inte är satt
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
        // Binda kategori_id om det finns
        $statement->bindValue(':category_id', $category_id);
    }
    // Exekvera frågan
    $statement->execute();
    // Hämta alla event
    $events = $statement->fetchAll();
    $statement->closeCursor();
    return $events;
}

function check_for_matching_events($prompt)
{
    global $db;
    // SQL-fråga för att hitta event som matchar prompten
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
    // Binda prompten
    $statement->bindValue(':prompt', $prompt);
    // Exekvera frågan
    $statement->execute();
    // Hämta alla matchande event
    $events = $statement->fetchAll();
    $statement->closeCursor();
    return $events;
}

function update_event($params)
{
    global $db;

    // Hämta och ta bort event_id från params
    $event_id = $params["event_id"];
    unset($params["event_id"]);

    // Hämta och ta bort host_ids från params om de finns
    $host_ids = isset($params['host_ids']) ? $params['host_ids'] : null;
    unset($params['host_ids']);

    // Ta bort alla parametrar som är tomma
    foreach ($params as $column => $value) {
        if (!$value) {
            unset($params[$column]);
        }
    }

    try {
        // Om det finns några parametrar att uppdatera
        if ($params) {
            // Bygg update-frågan
            $update_query = "UPDATE events SET ";
            $update_query .= implode(" = ?, ", array_keys($params)) . " = ? ";
            $update_query .= "WHERE event_id = ?";

            $statement = $db->prepare($update_query);
            $values = array_values($params);
            $values[] = $event_id;
            // Exekvera update-frågan
            $statement->execute($values);
            $statement->closeCursor();
        }

        // Om det finns nya host_ids
        if ($host_ids) {
            // Ta bort gamla värdar
            $query = "DELETE FROM event_hosts WHERE event_id = :event_id";
            $statement = $db->prepare($query);
            $statement->bindValue(":event_id", $event_id);
            $statement->execute();
            $statement->closeCursor();

            // Lägg till nya värdar
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
        // Om det blir fel, skriv ut felmeddelandet
        echo $e->getMessage();
    }
}

function delete_event($event_id)
{
    global $db;
    // Starta en transaktion
    $db->beginTransaction();

    // Ta bort alla värdar för eventet
    $query = 'DELETE FROM event_hosts WHERE event_id = :event_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':event_id', $event_id);
    $statement->execute();
    $statement->closeCursor();

    // Ta bort själva eventet
    $query = 'DELETE FROM events WHERE event_id = :event_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':event_id', $event_id);
    $statement->execute();
    $statement->closeCursor();

    // Avsluta transaktionen
    $db->commit();
}