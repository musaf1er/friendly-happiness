<?php $flash = take_flash(); $user = current_user(); $activeRoute = current_route($page ?? 'home'); $navItems = [['route' => 'home', 'label' => 'Home'], ['route' => 'about', 'label' => 'Charter'], ['route' => 'gallery', 'label' => 'Garage'], ['route' => 'events', 'label' => 'Rides'], ['route' => 'shop', 'label' => 'Goods'], ['route' => 'prospect', 'label' => 'Make contact']]; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#090909">
<meta name="description" content="Mischief Outlaws Motorcycle Club. Built by brothers, bound by the road.">
<title><?= e($title ?? 'Mischief Outlaws MC') ?></title>
<link rel="icon" type="image/png" href="assets/images/brand/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=New+Rocker&family=Oswald:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<div class="custom-cursor-dot" aria-hidden="true"></div><div class="custom-cursor-ring" aria-hidden="true"></div>
<header class="site-header"><div class="header-rule"></div><div class="shell nav-wrap"><a class="brand" href="<?= url() ?>"><img class="brand-emblem" src="assets/images/brand/mischiefs-center-patch.webp" alt=""><span><strong>Mischiefs</strong><small>Los Santos · MC</small></span></a><button class="nav-toggle" aria-expanded="false" aria-controls="nav">Menu</button><nav id="nav" class="nav-links" aria-label="Primary navigation"><?php foreach ($navItems as $item): $isActive = $activeRoute === $item['route']; ?><a class="nav-link <?= $isActive ? 'is-active' : '' ?> <?= $item['route'] === 'prospect' ? 'nav-cta' : '' ?>" href="<?= url($item['route']) ?>" <?= $isActive ? 'aria-current="page"' : '' ?>><?= e($item['label']) ?></a><?php endforeach; ?></nav></div></header>
<?php if ($flash): ?><div class="flash <?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div><?php endif; ?>
<main id="main">
