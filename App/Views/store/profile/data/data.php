<div class="data-page profile-sub-page">
	<div class="breadcrumb">
		<ol>
			<li><a href="<?php echo url('/profile'); ?>" data-translationKey="profile">Profile</a></li>
			<i class="fa-solid fa-chevron-right"></i>
			<li data-translationKey="dataManagement">Data Management</li>
		</ol>
	</div>
	<h2>Data Management</h2>
	<div class="data-table">
		<div class="table-row name">
			<div>
				<h5 data-translationKey="name">Name:</h5>
				<span><?php echo ($user->first_name . ' ' . $user->last_name) ?></span>
			</div>
			<a href="<?php echo url('/profile/data/name'); ?>"><button data-translationKey="edit">Edit</button></a>
		</div>
		<div class="table-row email">
			<div>
				<h5 data-translationKey="email">Email:</h5>
				<span><?php echo $user->email ?></span>
			</div>
			<a href="<?php echo url('/profile/data/email'); ?>"><button data-translationKey="edit">Edit</button></a>
		</div>
		<div class="table-row email">
			<div>
				<h5 data-translationKey="phone">Phone:</h5>
				<span><?php echo $user->phone ?></span>
			</div>
			<a href="<?php echo url('/profile/data/phone'); ?>"><button data-translationKey="edit">Edit</button></a>
		</div>
		<div class="table-row email">
			<div>
				<h5 data-translationKey="password">Password:</h5>
				<span>&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;</span>
			</div>
			<a href="<?php echo url('/profile/data/password'); ?>"><button data-translationKey="edit">Edit</button></a>
		</div>
	</div>
</div>