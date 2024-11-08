<div class="container">
    <h1 class="text-center mb-4"><?= $title ?? ''; ?></h1>
    <form class="w-25 m-auto" method="post" action="<?= base_url('/register') ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="name" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password">
        </div>
        <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" name="passwordConfirmation" class="form-control" id="passwordConfirmation">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>