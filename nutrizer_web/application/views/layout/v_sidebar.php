<div class="main-sidebar sidebar-style-2">
	<aside id="sidebar-wrapper">
		<div class="sidebar-brand">
			<a href="<?php echo base_url(); ?>"> <img alt="image" src="<?php echo base_url(); ?>loader/images/common/logo.png" class="header-logo" /> <span class="logo-name">Nutrizer</span>
			</a>
		</div>
		<ul class="sidebar-menu">
			<li class="menu-header">Main</li>
			<?php $userInfo = getUserInfo();
			if ($userInfo != false) :
					if ($userInfo['privilege_name'] == "Administrator") : ?>
					<!-- <li class="dropdown <?php echo ($pageId == "dashboard") ? "active" : ""; ?>">
						<a href="<?php echo base_url(); ?>dashboard" class="nav-link"><i data-feather="bar-chart-2"></i><span>Dashboard</span></a>
					</li> -->
					<li class="dropdown <?php echo ($pageId == "manage_user") ? "active" : ""; ?>">
						<a href="<?php echo base_url(); ?>manage/user" class="nav-link"><i data-feather="users"></i><span>Pengguna</span></a>
					</li>
					<li class="dropdown <?php echo ($pageId == "manage_kek") ? "active" : ""; ?>">
						<a href="<?php echo base_url(); ?>manage/news" class="nav-link"><i data-feather="info"></i><span>Info Covid</span></a>
					</li>
					<li class="dropdown <?php echo ($pageId == "manage_nutrition") ? "active" : ""; ?>">
						<a href="<?php echo base_url(); ?>manage/nutrition" class="nav-link"><i data-feather="coffee"></i><span>Nutrisi</span></a>
					</li>
					<li class="dropdown <?php echo ($pageId == "manage_food_cat") ? "active" : ""; ?>">
						<a href="<?php echo base_url(); ?>manage/foodCategory" class="nav-link"><i data-feather="pie-chart"></i><span>Kategori Makanan</span></a>
					</li>
					<li class="dropdown <?php echo ($pageId == "manage_food") ? "active" : ""; ?>">
						<a href="<?php echo base_url(); ?>manage/food" class="nav-link"><i data-feather="heart"></i><span>Makanan</span></a>
					</li>
					<li class="dropdown <?php echo ($pageId == "setting_mobile") ? "active" : ""; ?>">
						<a href="<?php echo base_url(); ?>setting/mobile" class="nav-link"><i data-feather="settings"></i><span>Atur Mobile</span></a>
					</li>
				<?php endif; ?>
			<?php endif; ?>
		</ul>
	</aside>
</div>