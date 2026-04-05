<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Harry Potter Characters'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(assetPath('css', 'app.css')); ?>">
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/d/d4/Hogwarts-Crest.png" type="image/png">
</head>
<body class="bg-night">

<!-- Dynamic video background -->
<div class="bg-viewport" aria-hidden="true">
    <video id="bg-video" autoplay muted loop playsinline preload="auto" data-current="">
        <source src="<?php echo htmlspecialchars(assetPath('media', 'img/hogwarts.mp4')); ?>" type="video/mp4">
    </video>
    <div class="bg-vignette"></div>
    <div class="bg-grain"></div>
</div>

<!-- Audio element (user-controlled) -->
<audio id="bg-audio" loop preload="metadata">
    <source src="<?php echo htmlspecialchars(assetPath('media', 'music.mp3')); ?>" type="audio/mpeg">
</audio>
