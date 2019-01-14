<!-- sidebar menu -->
<?php
	$segment =  Request::segment(2);
	$sub_segment =  Request::segment(3);
?>
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
	<div class="menu_section">
        <h3>General</h3>
		<ul class="nav side-menu">
			<li class="{{ ($segment == 'dashboard' ? 'active' : '') }}">
				<a href="<?=url('backend/dashboard');?>"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
            <?php
                if ($userinfo['user_level_id'] < 3):

            ?>
			<li class=" {{ ((($segment == 'setting') || ($segment == 'modules') || ($segment == 'access-control')) ? 'active' : '') }}">
				<a><i class="fa fa-cog"></i> System Admin <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="{{ ((($segment == 'setting') || ($segment == 'modules') || ($segment == 'access-control')) ? 'display : block' : '') }}">
					<li class="{{ ($segment == 'setting' ? 'active' : '') }}">
						<a href="<?=url('backend/setting');?>">Setting</a>
					</li>
					<?php
						// SUPER ADMIN //
						if ($userinfo['user_level_id'] == 1):
		
					?>
					<li class="{{ ($segment == 'modules' ? 'active' : '') }}">
						<a href="<?=url('backend/modules');?>">Modules</a>
					</li>
					<li class="{{ ($segment == 'access-control' ? 'active' : '') }}">
						<a href="<?=url('backend/access-control');?>">Access Control</a>
                    </li>
					<?php
						endif;
					?>
				</ul>
            </li>
            <?php
                endif;
            ?>
            <?php
                if ($userinfo['user_level_id'] < 3):
            ?>
			<li class=" {{ ((($segment == 'users-level') || ($segment == 'users-user')) ? 'active' : '') }}">
				<a><i class="fa fa-users"></i> Membership <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu" style="{{ ((($segment == 'users-level') || ($segment == 'users-user')) ? 'display : block' : '') }}">
                    <?php
                        if ($userinfo['user_level_id'] == 1):
                    ?>
					<li class="{{ ($segment == 'users-level' ? 'active' : '') }}">
						<a href="<?=url('backend/users-level');?>">Master User Level</a>
                    </li>
                    <?php
                        endif;
                    ?>
					<li class="{{ ($segment == 'users-user' ? 'active' : '') }}">
						<a href="<?=url('backend/users-user');?>">Master User</a>
					</li>
				</ul>
            </li>
            <?php
                endif;
            ?>
            <?php
                if ($userinfo['user_level_id'] == 1):
            ?>
			<li class="{{ ($segment == 'media-library' ? 'active' : '') }}">
				<a href="<?=url('backend/media-library');?>"><i class="fa fa-picture-o"></i> Media Library</a>
            </li>
            <?php
                endif;
            ?>
            <?php
            if ($userinfo['user_level_id'] < 3):
            ?>
            <li class="{{ ($segment == 'set-waktu' ? 'active' : '') }}">
                <a href="<?=url('backend/set-waktu');?>"><i class="fa fa-clock-o"></i> Set Waktu</a>
            </li>
            <?php
                endif;
            ?>
			<li class="{{ ($segment == 'workbook' ? 'active' : '') }}">
				<a href="<?=url('backend/workbook');?>"><i class="fa fa-book"></i> Workbook</a>
            </li>
            <?php
            if ($userinfo['user_level_id'] < 3):
            ?>
            <li class="{{ ($segment == 'general-report' ? 'active' : '') }}">
                <a href="<?=url('backend/general-report');?>"><i class="fa fa-bar-chart-o"></i> Gneeral Report</a>
            </li>
            <?php
                endif;
            ?>
		</ul>
    </div>
</div>

