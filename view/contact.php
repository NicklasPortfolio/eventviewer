<?php include 'view/shared/header.php' ?>
<link rel="stylesheet" href="assets/css/form.css">
<section class="d-flex justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="container" style="max-width: 400px;">
        <h2 class="text-center mb-4">Contact Us</h2>
        <form action="." method="post" autocomplete="off" class="border p-4 rounded">
            <input type="hidden" name="action" value="contact">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="username">Your Email:</label>
                    <input type="email" placeholder="Email Address" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="password">Issue Title:</label>
                    <input type="text" placeholder="Title" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="password">Issue Description:</label>
                    <textarea class="form-control" cols="12" rows="6" required></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-block">Send</button>
                </div>
            </div>
        </form>
        <?php if ($success_message) { ?>
            <div class="alert alert-warning mt-2 h-50">
                <p><?= $success_message ?></p>
            </div>
        <?php } ?>
    </div>
</section>
<?php include 'view/shared/footer.php' ?>