<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/add_event.css">
</head>

<body>
    <?php include 'view/shared/header.php'; ?>
    <section class="m-5">
        <?php if (isset($_SESSION["admin"])) { ?>
            <h2>Add Event</h2>
            <form action="." method="post" autocomplete="off">
                <input type="hidden" name="action" value="add_event">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="eventName">Name:</label>
                        <input type="text" id="eventName" name="event_name" class="form-control" maxlength="50"
                            placeholder="Name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="eventCategory">Category:</label>
                        <select id="eventCategory" name="category_id" class="form-control" required>
                            <option value="">Please select</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id']; ?>">
                                    <?= $category['event_category']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="eventDescription">Description:</label>
                        <input type="text" id="eventDescription" name="event_description" class="form-control"
                            maxlength="120" placeholder="Description" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="eventLocation">Location:</label>
                        <input type="text" id="eventLocation" name="event_location" class="form-control" maxlength="60"
                            placeholder="Location" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="eventDate">Date:</label>
                        <input type="datetime-local" id="eventDate" name="event_date" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="eventHosts">Hosts:</label>
                        <div id="selectedHostsContainer">
                            <input type="hidden" id="selectedHostIds" name="host_ids">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownHosts"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Select Hosts
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownHosts">
                                    <?php foreach ($hosts as $host): ?>
                                        <a class="dropdown-item host-option" href="#" data-host-id="<?= $host['host_id']; ?>">
                                            <?= $host['host_name']; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div id="selectedHosts"></div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="eventDate">Image Link (Optional):</label>
                        <input type="text" id="eventImg" name="event_img" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Event</button>
            </form>
        <?php } else { ?>
            <div class="alert alert-warning mt-2 h-50">
                <p>Administrator privileges are required to access this page.</p>
            </div>
        <?php } ?>
    </section>

    <?php include 'view/shared/footer.php' ?>

    <script src="assets/js/host_field_controller.js"></script>
</body>

</html>