<?php App\Services\View::extend('layout.main'); ?>

<?php App\Services\View::startSection('title');  ?>
Home
<?php App\Services\View::endSection();  ?>

<?php App\Services\View::startSection('content');  ?>
<div class="p-16">
    <form action="/user/update/<?=$user->id?>" method="POST" class="w-full gap-4 flex flex-col">
        <div class="w-full">
            <label class="input input-bordered flex items-center gap-2">
                <input type="text" class="grow" name="username" value="<?=$user->username?>" placeholder="Username" />
            </label>
        </div>
        <div class="w-full">
            <label class="input input-bordered flex items-center gap-2">
                <input type="text" class="grow" name="name" value="<?=$user->name?>" placeholder="Name" />
            </label>
        </div>
        <div class="w-full">
            <label class="input input-bordered flex items-center gap-2">
            <input type="password" class="grow" name="password" value="<?=$user->password?>" placeholder="Password" />
            </label>
        </div>
        <div class="w-full">
            <input type="submit" class="btn btn-accent">
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