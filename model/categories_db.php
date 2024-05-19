<?php

function add_category($event_category)
{
    global $db;
    $query = 'INSERT INTO event_categories (event_category) VALUES (:event_category)';
    $statement = $db->prepare($query);
    $statement->bindValue(":event_category", $event_category);
    $statement->execute();
    $statement->closeCursor();
}

function fetch_categories()
{
    global $db;
    $query = 'SELECT * FROM event_categories ORDER BY category_id';
    $statement = $db->prepare($query);
    $statement->execute();
    $categories = $statement->fetchAll();
    $statement->closeCursor();
    return $categories;
}

function fetch_category_name($category_id)
{
    if (!$category_id) {
        return 'All Categories';
    }
    global $db;
    $query = 'SELECT * FROM event_categories WHERE category_id = :category_id';
    $statement = $db->prepare($query);
    $statement->bindValue(":category_id", $category_id);
    $statement->execute();
    $category = $statement->fetch();
    $statement->closeCursor();
    $category_name = $category['event_category'];
    return $category_name;
}

function delete_category($category_id)
{
    global $db;
    $query = 'DELETE FROM event_categories WHERE category_id = :category_id';
    $statement = $db->prepare($query);
    $statement->bindValue(":category_id", $category_id);
    $statement->execute();
    $statement->closeCursor();
}