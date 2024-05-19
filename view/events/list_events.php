<?php include 'view/shared/header.php'; ?>
<div class="container my-5">
    <section>
        <header class="mb-4">
            <h1 class="mb-4">Events</h1>
            <form action="." method="post" class="form-inline">
                <input type="hidden" name="action" value="">
                <div class="form-group mb-2 mr-2">
                    <select name="category_id" class="form-control" required>
                        <option value="0">View All</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['category_id']; ?>" <?= ($category_id == $category['category_id']) ? 'selected' : '' ?>>
                                <?= $category['event_category']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Go</button>
            </form>
        </header>
        <?php if ($events) { ?>
            <?php foreach ($events as $event): ?>
                <div class="card mb-3 pr-2">
                    <div class="row g-0">
                        <div class="col-md-8 col-12">
                            <div class="card-body">
                                <h5 class="card-title"><?= $event['event_name'] . " (" . $event['event_category'] . ")" ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="20" height="20">
                                        <path
                                            d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z" />
                                    </svg>
                                    <?= empty($event['event_hosts']) ? 'No hosts present for this event' : $event['event_hosts'] ?>
                                </h6>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20" height="20">
                                        <path
                                            d="M384 476.1L192 421.2V35.9L384 90.8V476.1zm32-1.2V88.4L543.1 37.5c15.8-6.3 32.9 5.3 32.9 22.3V394.6c0 9.8-6 18.6-15.1 22.3L416 474.8zM15.1 95.1L160 37.2V423.6L32.9 474.5C17.1 480.8 0 469.2 0 452.2V117.4c0-9.8 6-18.6 15.1-22.3z" />
                                    </svg>
                                    <?= $event['event_location'] ?>
                                </h6>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20">
                                        <path
                                            d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z" />
                                    </svg>
                                    <?= $event['event_date'] ?>
                                </h6>
                                <p class="card-text"><?= $event['event_description']; ?></p>
                                <?php if (isset($_SESSION["admin"])) { ?>
                                <div>
                                    <form action="." method="post" class="d-inline delete-event-form">
                                        <input type="hidden" name="action" value="delete_event">
                                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">❌ Delete Event</button>
                                    </form>
                                    <form action="." method="post" class="d-inline">
                                        <input type="hidden" name="action" value="edit_event_page">
                                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                        <button type="submit" class="btn btn-info btn-sm">✏️ Edit Event</button>
                                    </form>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($event['event_img']) { ?>
                            <div class="col-md-4 d-none d-md-block py-2 pl-2">
                                <img src="<?= $event['event_img'] ?>" class="img-thumbnail rounded-end"
                                    alt="<?= $event['event_name'] ?>">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php } else { ?>
            <div class="alert alert-warning">
                <?php if ($category_id) { ?>
                    <p>No events with this category exist yet.</p>
                <?php } else { ?>
                    <p>No events exist yet.</p>
                <?php } ?>
            </div>
        <?php } ?>
    </section>
</div>
<script src="assets/js/delete_event_prompt.js"></script>
<?php include 'view/shared/footer.php'; ?>