<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a></li>
            <!-- <li>
                <form class="form-inline mr-auto">
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </li> -->
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle"><i data-feather="mail"></i>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    Messages
                    <div class="float-right">
                        <a href="javascript:;">Mark All As Read</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-icons">
                    <div class="dropdown-item"> <span class="dropdown-item-icon bg-secondary text-white"> <i class="fas
												fa-sad-tear"></i>
                        </span> <span class="dropdown-item-desc">No message yet...<span class="time">Create new one</span>
                        </span>
                    </div>
                </div>
                <div class="dropdown-footer text-center">
                    <a href="javascript:;">View All <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>
        <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg"><i data-feather="bell" class="bell"></i>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    Notifications
                    <div class="float-right">
                        <a href="javascript:;">Mark All As Read</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-icons">
                    <div class="dropdown-item"> <span class="dropdown-item-icon bg-secondary text-white"> <i class="fas
												fa-smile-beam"></i>
                        </span> <span class="dropdown-item-desc">Nothing to see here...<span class="time">Relax!</span>
                        </span>
                    </div>
                </div>
                <div class="dropdown-footer text-center">
                    <a href="javascript:;">View All <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>
        <?php $userInfo=getUserInfo();?>
        <li class="dropdown"><a href="javascript:;" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="<?php echo STREAM_URL; ?>loader/thumb/user/<?php echo $userInfo!=false?$userInfo['avatar']??"-":"_"; ?>" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                    <div class="dropdown-title"><?php echo $userInfo!=false?$userInfo['name']:"Please Login!"; ?></div>
                <a href="<?php echo base_url();?>profile/update" class="dropdown-item has-icon"> <i class="far
										fa-user"></i> Profile
                </a> <!--<a href="timeline.html" class="dropdown-item has-icon"> <i class="fas fa-bolt"></i>
                    Activities
                </a> <a href="#" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                    Settings
                </a> -->
                <div class="dropdown-divider"></div>
                <a href="<?php echo base_url(); ?>logout" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>