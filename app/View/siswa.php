<?php App\Services\View::extend('layout.main'); ?>

<?php App\Services\View::startSection('content');  ?>
<ul>
    <h1>INI HALAMAN <?=$judul?></h1>
</ul>
<?php App\Services\View::endSection();  ?>


<?php App\Services\View::startSection('head');  ?>
<style>
    .coba {
        display: flex;
    }
</style>
<?php App\Services\View::endSection();  ?>


<?php App\Services\View::startSection('js');  ?>
<script>

fetch("/",
{
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    method: "GET",
})
.then(function(res){ return res.json() })
.then(function(data){ console.log(data) })
.catch(function(res){ console.log(res) })


</script>
<?php App\Services\View::endSection();  ?>