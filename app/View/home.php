<?php App\Services\View::extend('layout.main'); ?>

<?php App\Services\View::startSection('title');  ?>
Home
<?php App\Services\View::endSection();  ?>

<?php App\Services\View::startSection('content');  ?>
<div class="p-16">
    <?php
    
    //session_start();
    echo $_SESSION['user'];

    ?>
    <div class="w-full">
            <a href="/user/add" class="btn btn-accent">Add</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table">
            <!-- head -->
            <thead>
            <tr>
                <th></th>
                <th>Username</th>
                <th>Name</th>
                <th>Password</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <!-- row 1 -->
             <?php foreach ($users as $key => $value) { ?>
                <tr>
                    <th><?=$key+1?></th>
                    <td><?=$value->username?></td>
                    <td><?=$value->name?></td>
                    <td><?=$value->password?></td>
                    <td>
                        <a href="/user/edit/<?=$value->id?>" class="btn btn-info">Edit</a>

                        <form action="/user/delete/<?=$value->id?>" method="post">
                            <input type="submit" class="btn btn-error" value="Hapus">
                        </form>

                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
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