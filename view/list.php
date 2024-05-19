<?php include 'view/shared/header.php'; ?>

<div class="container my-5">
    <section>
        <?php if (isset($_SESSION["admin"])) { ?>
            <?php if (!empty($categories)) { ?>
                <h2>Categories</h2>
                <?php foreach ($categories as $category): ?>
                    <div class="card mb-3 pr-2">
                        <div class="row g-0">
                            <div class="col-md-8 col-12">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $category['event_category'] ?></h5>
                                    <form action="." method="post" class="d-inline delete-event-form">
                                        <input type="hidden" name="action" value="delete_category">
                                        <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">❌ Delete Category</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <form action="." method="post" class="d-inline">
                    <input type="hidden" name="action" value="add_category_page">
                    <button class="btn btn-success">Add new category</button>
                </form>
            <?php } else if (!empty($hosts)) { ?>
                    <h2>Hosts</h2>
                <?php foreach ($hosts as $host): ?>
                        <div class="card mb-3 pr-2">
                            <div class="row g-0">
                                <div class="col-md-8 col-12">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $host['host_name'] ?></h5>
                                        <form action="." method="post" class="d-inline delete-event-form">
                                            <input type="hidden" name="action" value="delete_host">
                                            <input type="hidden" name="host_id" value="<?= $host['host_id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">❌ Delete Host</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
                    <form action="." method="post" class="d-inline">
                        <input type="hidden" name="action" value="add_host_page">
                        <button class="btn btn-success">Add new host</button>
                    </form>
            <?php } else if (!empty($users)) { ?>
                        <h2>Users</h2>
                <?php foreach ($users as $user): ?>
                            <div class="card mb-3 pr-2">
                                <div class="row g-0">
                                    <div class="col-md-8 col-12">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $user['username'] ?></h5>
                                            <form action="." method="post" class="d-inline delete-event-form">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">❌ Delete User</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                <?php endforeach; ?>
                        <form action="." method="post" class="d-inline">
                            <input type="hidden" name="action" value="add_user_page">
                            <button class="btn btn-success">Add new user</button>
                        </form>
            <?php } else { ?>
                        <div class="alert alert-warning">
                            <p>No entries in this table.</p>
                        </div>
                        <form action="." method="post" class="d-inline">
                            <input type="hidden" name="action" value="add_category_page">
                            <button class="btn btn-success">Add new category</button>
                        </form>
                        <form action="." method="post" class="d-inline">
                            <input type="hidden" name="action" value="add_host_page">
                            <button class="btn btn-success">Add new host</button>
                        </form>
                        <form action="." method="post" class="d-inline">
                            <input type="hidden" name="action" value="add_user_page">
                            <button class="btn btn-success">Add new user</button>
                        </form>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-warning mt-2 h-50">
                <p>Administrator privileges are required to access this page.</p>
            </div>
        <?php } ?>
    </section>
</div>
<script src="assets/js/delete_event_prompt.js"></script>
<?php include 'view/shared/footer.php'; ?>