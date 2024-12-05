<?php App\Services\View::extend('layout.main'); ?>

<?php App\Services\View::startSection('title');  ?>
Login
<?php App\Services\View::endSection();  ?>

<?php App\Services\View::startSection('content');  ?>
<div class="p-16">
<form action="/login" method="POST" class="w-full gap-4 flex flex-col">
        <div class="w-full">
            <label class="input input-bordered flex items-center gap-2">
                <input type="text" class="grow" value="<?=App\Services\Input::old('username','');?>" name="username" placeholder="Username" />
            </label>
             <!-- Menampilkan pesan error -->
            <?php if ($error = App\Services\Flash::get('error_username')): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
        <div class="w-full">
            <label class="input input-bordered flex items-center gap-2">
            <input type="password" class="grow" value="<?=App\Services\Input::old('password','');?>" name="password" placeholder="Password" />
            </label>
            <!-- Menampilkan pesan error -->
            <?php if ($error = App\Services\Flash::get('error_password')): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
        <?php if ($error = App\Services\Flash::get('login_error')): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
        <div class="w-full">
            <input type="submit" value="Login" class="btn btn-accent">
        </div>
    </form>
</div>
<?php App\Services\View::endSection();  ?>


<?php App\Services\View::startSection('head');  ?>
<style>
    .coba {
        display: flex;
    }
</style>
<?php App\Services\View::endSection();  ?>


<?php App\Services\View::startSection('js');  ?>
<script src="app.js"></script>
<?php App\Services\View::endSection();  ?>