<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
        <script src="https://cdn.tailwindcss.com"></script>
        <title><?php App\Services\View::yieldSection('title'); ?></title>
    </head>

    <?php App\Services\View::include('layout.heading'); ?>
    <?php App\Services\View::yieldSection('head'); ?>
</head>
<body>
    <?php App\Services\View::include('layout.navbar'); ?>
    <div class="w-full">
        <?php App\Services\View::yieldSection('content'); ?>
    </div>
    <?php App\Services\View::include('layout.footer'); ?>

    <?php App\Services\View::yieldSection('js'); ?>
</body>
</html>