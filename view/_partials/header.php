<?php 
	$dots = array_key_exists("url", $_GET) ? explode("/", $_GET['url']) : ['dashboard'];
	switch(count($dots)){
		case 0: case 1: $dots = ''; break;
		case 2: $dots = '../'; break;
		case 3: $dots = '../../'; break;
		default: $dots = '../../../'; break;
	}
?>

<!DOCTYPE html>
<html lang="pt-BR" dir="ltr">
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="<?= $dots; ?>public/css/dashboard.css">
		<link rel="stylesheet" href="<?= $dots; ?>public/css/form.css">
		<link rel="stylesheet" href="<?= $dots; ?>public/css/toastr.min.css">
		<link rel="stylesheet" href="<?= $dots; ?>public/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="<?= $dots; ?>public/css/vex.min.css">
		<link rel="stylesheet" href="<?= $dots; ?>public/css/vex-theme-flat-attack.min.css">
		<link rel="stylesheet" href="<?= $dots; ?>public/css/vex-theme-top.min.css">
		<link rel="stylesheet" href="<?= $dots; ?>public/css/vex-theme-bottom-right-corner.min.css">
		<link rel="stylesheet" href="<?= $dots; ?>public/css/fullcalendar-main.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
		<script type="text/javascript" src="<?= $dots; ?>public/js/jquery.min.js"></script>
		<script type="text/javascript" src="<?= $dots; ?>public/js/toastr.min.js"></script>
		<script type="text/javascript" src="<?= $dots; ?>public/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="<?= $dots; ?>public/js/vex.combined.min.js"></script>
		<script type="text/javascript" src="<?= $dots; ?>public/js/fullcalendar-main.min.js"></script>
		<script type="text/javascript" src="<?= $dots; ?>public/js/fullcalendar-locales-all.min.js"></script>
		<script type="text/javascript" src="<?= $dots; ?>public/js/jquery.mask.min.js"></script>
		<script type="text/javascript" src="<?= $dots; ?>public/js/dashboard.js"></script>
	</head>
	<body>
    	<input type="checkbox" id="check">
    	<!--header area start-->
		<header>
			<label for="check">
				<i class="fas fa-bars" id="sidebar_btn"></i>
			</label>
			<div class="left_area">
				<h3><span>Quokka</span> </h3> 
			</div>
			<div class="right_area">
				<button onclick="logout()" class="logout_btn">
					<i class="fas fa-sign-out-alt fa-lg"></i>
				</button>
			</div>
		</header>

		<!--header area end-->
		<!--mobile navigation bar start-->
		<div class="mobile_nav">
			<div class="nav_bar">
				<img src="<?= $dots; ?>public/img/user.png" class="mobile_profile_image" alt="">
				<i class="fa fa-bars nav_btn"></i>
			</div>
			<div class="mobile_nav_items">
				<a href="<?= $dots; ?>dashboard"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
				<a href="<?= $dots; ?>users" id="url-user-prefix"><i class="fas fa-users-cog"></i><span>Usuários</span></a>
				<a href="<?= $dots; ?>customers" id="url-customer-prefix"><i class="fas fa-users"></i><span>Clientes</span></a>
				<a href="<?= $dots; ?>schedules"><i class="fas fa-calendar-alt"></i><span>Agendamentos</span></a>
				<a href="#"><i class="fas fa-cogs"></i><span>Configurações</span></a>
			</div>
		</div>

		<!--mobile navigation bar end-->
		<!--sidebar start-->
		<div class="sidebar">
			<div class="profile_info">
				<img src="<?= $dots; ?>public/img/user.png" class="profile_image" alt="">
				<h4 id="name-user"><?= $_SESSION['user']['name'] ?></h4>
			</div>
			<div>
				<a href="<?= $dots; ?>dashboard"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
				<a href="<?= $dots; ?>users" id="url-user-prefix"><i class="fas fa-users-cog"></i><span>Usuários</span></a>
				<a href="<?= $dots; ?>customers" id="url-customer-prefix"><i class="fas fa-users"></i><span>Clientes</span></a>
				<a href="<?= $dots; ?>schedules"><i class="fas fa-calendar-alt"></i><span>Agendamentos</span></a>
				<a href="#"><i class="fas fa-cogs"></i><span>Configurações</span></a>
			</div>
		</div>
		<div class="content">
