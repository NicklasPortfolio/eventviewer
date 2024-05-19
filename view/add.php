<?php include 'view/shared/header.php'; ?>
<section class="m-5">
    <?php if (isset($_SESSION["admin"])) { ?>
        <?php if ($add_page == "categories") { ?>
            <h2>Add a Category</h2>
            <form action="." method="post" autocomplete="off">
                <input type="hidden" name="action" value="add_category">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Category name:</label>
                        <input type="text" name="category_name" class="form-control" maxlength="50" placeholder="Category name"
                            required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Category</button>
            </form>
        <?php } else if ($add_page == "hosts") { ?>
                <h2>Add a Host</h2>
                <form action="." method="post" autocomplete="off">
                    <input type="hidden" name="action" value="add_host">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Host Forename:</label>
                            <input type="text" name="host_forename" class="form-control" maxlength="50" placeholder="Host forename"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Host Surname:</label>
                            <input type="text" name="host_surname" class="form-control" maxlength="50" placeholder="Host surname"
                                required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Add Host</button>
                </form>
        <?php } else if ($add_page == "users") { ?>
                    <h2>Add a User</h2>
                    <form action="." method="post" autocomplete="off">
                        <input type="hidden" name="action" value="add_user">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Username:</label>
                                <input type="text" name="username" class="form-control" maxlength="50" placeholder="Username" required>
                            </div>
                            <div class="form-group col-md-6" id="app">
                                <div class="d-flex align-items-center mb-2">
                                    <label class="m-0 mr-2">Password:</label>
                                    <div v-if="passwordError" class="text-danger">{{ passwordError }}</div>
                                </div>
                                <input v-model="password" type="password" name="password" class="form-control" maxlength="50"
                                    placeholder="Password" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Add User</button>
                    </form>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-warning mt-2 h-50">
            <p>Administrator privileges are required to access this page.</p>
        </div>
    <?php } ?>
</section>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="assets/js/check_password.js"></script>
<?php include 'view/shared/footer.php' ?>