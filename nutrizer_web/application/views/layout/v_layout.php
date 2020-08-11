<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]> <html lang="en"><![endif]-->
<!-- Begin Head -->

<head>
    <?php $this->load->view('layout/v_header', $pageTitle); ?>
    <?php echo $pageStyle; ?>
</head>

<body>
    <!----Loader Start---->
    <div class="loader"></div>
    <!----Main Wrapper Start---->
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <?php $this->load->view('layout/v_topbar'); ?>
            <?php $this->load->view('layout/v_sidebar'); ?>

            <!---Main Content Start--->
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <?php $this->load->view('layout/v_content'); ?>
                </section>
                <?php $this->load->view('layout/v_rightbar'); ?>
            </div>
            <!----Main div close---->
            <?php $this->load->view('layout/v_footer'); ?>
        </div>
    </div>


    <script>
        var BASE_URL = "<?php echo base_url(); ?>";
        var API_URL ="<?php echo base_url(); ?>webapi/";
        var API_KEY = "<?php echo encryptData(MY_API_KEY); ?>";
    </script>
    <?php $this->load->view('layout/v_script'); ?>
    <?php echo $pageScript; ?>

    <?php $userInfo = getUserInfo();
			if ($userInfo != false) :
                if ($userInfo['privilege_name'] == "Analyzer") :?>
                    <script>
                        $(".add_modal").parent().html("");
                    </script>
                <?php endif;?>
    <?php    else:?>      
        <script>
        $(".add_modal").parent().html("");
        </script>
    <?php endif;?>      
    <!-- add_modal -->
</body>

</html>