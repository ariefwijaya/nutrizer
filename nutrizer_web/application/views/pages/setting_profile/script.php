<script src="<?php echo base_url(); ?>assets/bundles/jquery-pwstrength/jquery.pwstrength.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/upload-preview/assets/js/jquery.uploadPreview.min.js"></script>
<script>
    var apiEdit = API_URL + "profile/edit";
    var apiGet = API_URL + "profile/get";
    $(document).ready(function() {
        $.uploadPreview({
            input_field: ".image-preview #image-upload", //"#avatar_img_image-preview #image-upload", // Default: .image-upload
            preview_box: ".image-preview", //"#avatar_img_image-preview", // Default: .image-preview
            label_field: ".image-preview #image-label", //"#avatar_img_image-preview #image-label", // Default: .image-label
            label_default: "Choose File", // Default: Choose File
            label_selected: "Change File", // Default: Change File
            no_label: false, // Default: false
            success_callback: null // Default: null
        });

        $(".pwstrength").pwstrength();

        $("#apply-filter").click(function(e) {
            var formSelector = "#formData";
            var btnEl = $(formSelector + " button[type='submit']");
            btnEl.addClass("btn-progress");
            $.ajax({
                type: "POST",
                url: apiGet,
                dataType: 'json',
                headers: {
                    api_key: API_KEY
                },
                success: function(response, status, xhr) {
                    if (response.status) {
                        var data = response.data;
                        for (const key in data) {
                            if (data.hasOwnProperty(key)) {
                                const element = data[key];
                                if (key == "avatar_img" && element != null) {
                                    $("#" + key + "_image-preview").attr("style", "background-image: url(" + BASE_URL + "loader/thumb/user/" + element + ")");
                                    $("#formData ." + key + "_block-text span").html("You can upload new to change current image.<br>");
                                } else {
                                    $(formSelector + " [name='" + key + "']").val(element);
                                }
                            }
                        }

                        $("#formData .password_block-text").html('Leave this blank if you don\'t want to change the password');
                        // $.showToast("Data Refreshed", 'success');
                    } else {
                        $.showToast(response.error, "error");
                    }
                },
                complete: function(jqXHR, textStatus) {
                    btnEl.removeClass("btn-progress");
                },
                error: function(httpRequest, textStatus, errorThrown) {
                    // console.log("Error: " + textStatus + " " + errorThrown + " " + httpRequest);
                    var errorMsg = textStatus + " " + errorThrown;
                    $.showToast(errorMsg, 'error');
                }
            });
        });

        $("#formData").submit(function(e) {
            var form = $(this);
            var formSelector = "#" + $(this).attr("id");
            e.preventDefault();
            e.stopPropagation();
            $(formSelector + " button[type='submit']").addClass("btn-progress");
            $(formSelector + " input").removeClass("d-block");
            $(formSelector + ' div.invalid-feedback').html('');
            var urlSubmit = apiEdit;
            $.ajax({
                type: "POST",
                url: urlSubmit,
                data: new FormData(form[0]),
                processData: false,
                contentType: false,
                // cache:false,
                // async:false,
                dataType: 'json',
                headers: {
                    api_key: API_KEY
                },
                success: function(response, status, xhr) {
                    if (response.status) {
                        $("#apply-filter").click();
                        $.showToast(response.data, 'success');
                    } else {
                        var validationMessage = response.validation;
                        if (validationMessage) {
                            var idx = 0;
                            for (const key in validationMessage) {
                                if (validationMessage.hasOwnProperty(key)) {
                                    const element = validationMessage[key];
                                    if (idx == 0) {
                                        $("[name='" + key + "']").focus();
                                    }
                                    var inputEl = $("[name='" + key + "']").siblings('div.invalid-feedback');
                                    inputEl.html(element);
                                    inputEl.addClass("d-block");
                                }
                                idx++;
                            }
                        } else {
                            $.showToast(response.error, 'error');
                        }
                    }
                },
                complete: function(jqXHR, textStatus) {
                    $(formSelector + " button[type='submit']").removeClass("btn-progress");
                },
                error: function(httpRequest, textStatus, errorThrown) {
                    // "We couldn't complete your request"
                    var errorMsg = textStatus + " " + errorThrown;
                    $.showToast(errorMsg, 'error');
                }
            });
        });

        $("#apply-filter").click();
    });
</script>