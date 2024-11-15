<div class="container">
    <h1 class="text-center mb-4"><?= $title ?? ''; ?></h1>

    <form class="w-25 m-auto" method="post" action="<?= base_url('/register') ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control <?= get_class_validation('name'); ?>" id="name" aria-describedby="emailHelp" value="<?= old('name'); ?>">
            <?= get_errors('name') ?>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control <?= get_class_validation('email'); ?>" id="email" aria-describedby="emailHelp" value="<?= old('email'); ?>">
            <?= get_errors('email') ?>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control <?= get_class_validation('password'); ?>" id="password">
            <?= get_errors('password') ?>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
            <?= get_errors('password_confirmation') ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <?php
    session()->remove('form_data');
    session()->remove('form_errors');
    ?>
</div>