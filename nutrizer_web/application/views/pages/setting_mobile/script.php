<script>
    var apiEdit = BASE_URL + "webapi/banner/update";
    var apiGet = BASE_URL + "webapi/banner";
    $(document).ready(function() {
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
                                if(key=="news_status"){
                                    $(formSelector+" [name='" + key + "']").prop( "checked", element );
                                }else{
                                    $(formSelector+" [name='" + key + "']").val(element);
                                }
                                
                            }
                        }
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