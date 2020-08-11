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
        <section class="section">
            <?php $this->load->view('layout/v_content'); ?>
        </section>
    </div>

    <script>
        var BASE_URL = "<?php echo base_url(); ?>";
        var API_URL ="<?php echo base_url(); ?>webapi/";
        var API_KEY = "<?php echo encryptData(MY_API_KEY); ?>";
    </script>
    <?php $this->load->view('layout/v_script'); ?>
    <?php echo $pageScript; ?>
</body>

</html>