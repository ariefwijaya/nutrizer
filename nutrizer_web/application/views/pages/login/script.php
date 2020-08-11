
<script>
  

    $(function() {
        var formSelector = "#loginForm";
        var submitBtn = $(formSelector + " button[type='submit']");

        $(formSelector).submit(function(e) {
            var form = $(this);
       
                e.preventDefault();
                e.stopPropagation();
                submitBtn.addClass("btn-progress");
                $(formSelector+ " input").removeClass("d-block")
                $.ajax({
                    type: "POST",
                    url: API_URL + "login",
                    data: {"username":$("#username").val(),"password":$("#password").val()},
                    dataType: 'json',
                    headers: {
                        api_key: API_KEY
                    },
                    success: function(response, status, xhr) {
                        if (response.status) {
                            $.showToast(response.data, 'success');
                            window.setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            var validationMessage = response.validation;
                            if (validationMessage) {
                                for (let index = 0; index < validationMessage.length; index++) {
                                    const element = validationMessage[index];
                                    var inputEl = $(formSelector + " [name='" + element.name + "']").parent().find('div.invalid-feedback');
                                    inputEl.html(element.error);
                                    inputEl.addClass("d-block");
                                }
                            } else {
                                $.showToast(response.error, 'error');
                            }
                        }
                    },
                    complete: function(jqXHR, textStatus) {
                        submitBtn.removeClass("btn-progress");
                    },
                    error: function(httpRequest, textStatus, errorThrown) {
                        // "We couldn't complete your request"
                        var errorMsg = textStatus + " " + errorThrown;
                        $.showToast(errorMsg, 'error');
                    }
                });
        });
    });
</script>