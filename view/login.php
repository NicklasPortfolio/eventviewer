<?php include 'view/shared/header.php' ?>
<link rel="stylesheet" href="assets/css/form.css">
<section class="d-flex justify-content-center align-items-center min-vh">
    <div class="container" style="max-width: 400px;">
        <h2 class="text-center mb-4">Login</h2>
        <form action="." method="post" autocomplete="off" class="border p-4 rounded">
            <input type="hidden" name="action" value="login">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Username" class="form-control"
                        required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password" class="form-control"
                        required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </div>
            </div>
        </form>
        <?php if ($login_error) { ?>
            <div class="alert alert-warning mt-2 h-50">
                <p><?= $login_error ?></p>
            </div>
        <?php } ?>
    </div>
</section>
<?php include 'view/shared/footer.php' ?>