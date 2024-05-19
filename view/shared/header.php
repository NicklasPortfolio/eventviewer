<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Viewer</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/header.css">
</head>

<body>
    <header class="navbar navbar-expand-lg navbar-dark sticky-top fw-bold">
        <nav class="container-fluid d-flex justify-content-center align-items-center" aria-label="Main navigation">
            <a class="navbar-brand d-flex align-items-center mr-3" href=".">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="bi" width="28" height="28"
                    fill="currentColor">
                    <path
                        d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H64C28.7 64 0 92.7 0 128v16 48V448c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H344V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H152V24zM48 192H400V448c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192z" />
                </svg>
                <h5 class="m-0 ml-2">
                    Event Viewer
                </h5>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mr-3" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <?php if (isset($_SESSION["admin"])) { ?>
                            <a class="nav-link" href=".?action=logout">Log out</a>
                        <?php } else { ?>
                            <a class="nav-link" href=".?action=login_page">Login</a>
                        <?php } ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=".?action=contact_page">Contact</a>
                    </li>
                    <?php if (isset($_SESSION["admin"])) { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Admin Panel
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href=".?action=add_event_page">Add an event</a>
                                <a class="dropdown-item" href=".?action=list_categories">View/edit categories</a>
                                <a class="dropdown-item" href=".?action=list_hosts">View/edit hosts</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href=".?action=list_users">View/edit users</a>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <form class="form-inline my-2 my-lg-0 mr-3" role="search" action="." method="post" autocomplete="off">
                <input type="hidden" name="action" value="search_event">
                <input class="form-control me-2" type="search" name="search_prompt" placeholder="Search event title"
                    aria-label="Search" required>
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <a href="https://github.com/NicklasPortfolio" class="ms-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-github"
                    viewBox="0 0 16 16">
                    <path
                        d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82a7.62 7.62 0 0 1 2-.27c.68.003 1.36.092 2 .27 1.53-1.03 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.015 8.015 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                </svg>
            </a>
        </nav>
    </header>