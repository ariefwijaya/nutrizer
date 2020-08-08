<div class="modal fade" id="formModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="" id="formData" method="POST">
                <div class="modal-header">
                    <h5 id="formModalTitle" class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" />
                    <div class="form-group">
                        <label>Username</label>
                        <input name="username" type="text" class="form-control" placeholder="Must not contain spaces, special characters, or emoji." required>
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input name="name" type="text" class="form-control" required>
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Profile</label>
                        <textarea name="profile" class="form-control" placeholder="Description about the user"></textarea>
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control">
                            <option value="" selected="selected">UNSPECIFIED</option>
                            <option value="OTHERS">OTHERS</option>
                            <option value="MALE">MALE</option>
                            <option value="FEMALE">FEMALE</option>
                        </select>
                        <div class="invalid-feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Birth Date</label>
                        <input name="birth_date" type="date" class="form-control">
                        <div class="invalid-feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control">
                        <div class="invalid-feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input name="password" type="password" class="form-control pwstrength" data-indicator="pwindicator">
                        <div class="invalid-feedback">

                        </div>
                        <div id="pwindicator" class="pwindicator">
                            <div class="bar"></div>
                            <div class="label"></div>
                        </div>
                        <small class="password_block-text block-text form-text text-muted">
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input name="password_confirm" type="password" class="form-control">
                        <div class="invalid-feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Avatar</label>
                        <div id="avatar_img_image-preview" class="image-preview">
                            <label for="image-upload" id="image-label">Choose Image</label>
                            <input type="file" name="avatar_img" id="image-upload" accept=".jpg,.jpeg,.png" />
                            <div class="invalid-feedback">

                            </div>
                        </div>
                        <small class="avatar_img_block-text block-text form-text text-muted">
                            <span></span>Width and Height must greater than 100px . And Recommended to upload Square Image Size. Maximum size is 2 Megabytes(MB).
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Subscribe Newsletter</label>
                        <select name="subscribe_newsletter" class="form-control">
                            <option value="YES" selected="selected">YES</option>
                            <option value="NO">NO</option>
                        </select>
                        <div class="invalid-feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Locked</label>
                        <select name="islocked" class="form-control">
                            <option value="0" selected="selected">NO</option>
                            <option value="1">YES</option>
                        </select>
                        <div class="invalid-feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Is Accept Term & Condition</label>
                        <select name="is_accept_term" class="form-control" required>
                            <option value="1" selected="selected">YES</option>
                            <option value="0">NO</option>
                        </select>
                        <div class="invalid-feedback">

                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="submit" class="save_btn btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="formDeleteModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="formDeleteModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="" id="formDeleteData" method="POST">
                <div class="modal-header">
                    <h5 id="formDeleteModalTitle" class="modal-title">Delete this data?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" />
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remark" class="form-control" placeholder="Please give remarks to delete data!"></textarea>
                        <div class="invalid-feedback">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="submit" class="save_btn btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/bundles/jquery-pwstrength/jquery.pwstrength.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/upload-preview/assets/js/jquery.uploadPreview.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/aggrid/ag-grid-enterprisev20.min.js"></script>
<script>
    "use strict";
    // let the grid know which columns and what data to use
    var gridOptions = {
        columnDefs: [],
        // rowData: rowData,
        rowModelType: 'serverSide',
        purgeClosedRowNodes: false,
        rowHeight: 40,
        pagination: true,
        paginationPageSize: 50,
        cacheOverflowSize: 1,
        maxBlocksInCache: 4,
        infiniteInitialRowCount: 1,
        maxConcurrentDatasourceRequests: 2,
        cacheBlockSize: 50,
        enableCellChangeFlash: true,
        // serverSideSortingAlwaysResets: true,
        suppressEnterpriseResetOnNewColumns: true,
        blockLoadDebounceMillis: 300,
        // suppressPivots:true,
        // getRowNodeId: function(item) {
        //     return item.username.toString();
        // },
        getChildCount: function(data) {
            if (data === undefined) {
                return null;
            } else {
                return data.childCount;
            }
        },
        animateRows: false,
        multiSortKey: 'ctrl',
        components: {
            'actionCellRenderer': actionCellRenderer,
            'thumbImageRenderer': thumbImageRenderer,
            'booleanCellRenderer': booleanCellRenderer
        },
        // rowSelection: 'multiple',
        rowDeselection: true,
        enableRangeSelection: true,
        // enableCellTextSelection: true,
        clipboardDeliminator: ',',
        sideBar: true,
        onGridReady: function(params) {
            params.api.closeToolPanel();
        },
        deltaRowDataMode: true,
        overlayLoadingTemplate: '<span class="ag-overlay-loading-center">Something Error happened</span>',
        overlayNoRowsTemplate: '<span class="ag-overlay-loading-center">No Rows Found</span>'
    };


    function actionCellRenderer(params) {
        if (params.data != undefined && params.data.username != undefined) {
            var idVal = encodeURI(params.data.username);
            return '<div class="buttons">' +
                '<button onclick="editFormModal(this)" data-id="' + idVal + '" class="btn btn-icon btn-primary btn-sm edit_modal"><i class="far fa-edit"></i></button>' +
                '<button onclick="deleteFormModal(this)" data-id="' + idVal + '" class="btn btn-icon btn-danger btn-sm delete_modal"><i class="fas fa-times"></i></button>' +
                '</div>';
        } else {
            return '';
        }
    }

    function booleanCellRenderer(params) {
        if (params.value != undefined) {
            var valData = encodeURI(params.value);
            return (valData == 1) ? 'YES' : (valData == 0) ? 'NO' : null;
        } else {
            return '';
        }
    }

    function thumbImageRenderer(params) {
        if (params.value != undefined) {
            var valData = encodeURI(params.value);
            return '<a ' + (params.value ? 'href="' + (STREAM_URL + 'images/user/' + valData) + '" target="_blank"' : 'href="javascript:;"') + '> <div class="small-avatar zoomhover" alt="image" style="background-image:url(' + STREAM_URL + 'thumb/user/' + valData + ');" ></div></a>';
        } else {
            return '';
        }
    }

    var formState;
    var goToPageGrid = null;
    var apiGrid = BASE_URL + "api/app/users";
    var apiAdd = BASE_URL + "api/app/user/add";
    var apiEdit = BASE_URL + "api/app/user/edit";
    var apiDelete = BASE_URL + "api/app/user/delete";
    var apiGet = BASE_URL + "api/app/user/get/";

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

        $('#filter-text-box').change(350, $.debounce(function() {
            gridOptions.api.onFilterChanged();
        }));

        $("#apply-filter").click(function(e) {
            e.preventDefault();
            if (gridOptions.api !== undefined) {
                gridOptions.api.destroy();
            }
            var eGridDiv = document.querySelector('#ag-table');
            new agGrid.Grid(eGridDiv, gridOptions);

            const datasource = {
                getRows: (params) => getRowsData(params),
            };
            gridOptions.api.setServerSideDatasource(datasource);
        });


        $("#export-excel").click(function(e) {
            getRowsDataExcel();
        });


        $("button.add_modal").click(function(e) {
            e.preventDefault();
            clearForm();
            formState = "add";
            $("#formModal" + " .modal-title").html('Add New User');
            $("#formModal" + " button.save_btn").html('Create');
            $("#formModal").modal('show');
        });

        $("#formData").submit(function(e) {
            var form = $(this);
            var formSelector = "#" + $(this).attr("id");
            e.preventDefault();
            e.stopPropagation();
            $(formSelector + " button[type='submit']").addClass("btn-progress");
            $(formSelector + " input").removeClass("d-block");
            $(formSelector + ' div.invalid-feedback').html('');
            var urlSubmit;
            if (formState == "add") {
                urlSubmit = apiAdd;
            } else if (formState == "edit") {
                urlSubmit = apiEdit;
            } else {
                $.showToast("Oops, Something Error!", 'error');
                return;
            }
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
                        var currentPage = gridOptions.api.paginationGetCurrentPage();
                        var totalPage = gridOptions.api.paginationGetTotalPages();
                        gridOptions.api.purgeServerSideCache();
                        if (totalPage > currentPage) {
                            goToPageGrid = currentPage;
                        } else {
                            goToPageGrid = null;
                        }

                        $.showToast(response.data, 'success');
                        $("#formModal").modal('hide');
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

        $("#formDeleteData").submit(function(e) {
            var form = $(this);
            var formSelector = "#" + $(this).attr("id");
            e.preventDefault();
            e.stopPropagation();
            $(formSelector + " button[type='submit']").addClass("btn-progress");
            $(formSelector + " input").removeClass("d-block");
            $(formSelector + ' div.invalid-feedback').html('');
            $.ajax({
                type: "POST",
                url: apiDelete,
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
                        var currentPage = gridOptions.api.paginationGetCurrentPage();
                        var totalPage = gridOptions.api.paginationGetTotalPages();
                        gridOptions.api.purgeServerSideCache();
                        if (totalPage > currentPage) {
                            goToPageGrid = currentPage;
                        } else {
                            goToPageGrid = null;
                        }

                        $.showToast(response.data, 'success');
                        $("#formDeleteModal").modal('hide');
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




    function editFormModal(thisData) {
        clearForm();
        formState = "edit";
        var thisEl = $(thisData);
        thisEl.addClass("btn-progress");
        $("#formData [name='username']").attr("disabled", true);
        var id = thisEl.data('id');
        getFormData(id, thisEl);
    }


    function deleteFormModal(thisData) {
        var thisEl = $(thisData);
        var id = thisEl.data('id');
        $("#formDeleteData [name='id']").val(id);
        $("#formDeleteModal").modal('show');
    }

    function getFormData(id, thisEl) {
        $.ajax({
            type: "POST",
            url: apiGet + id,
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
                                $("#" + key + "_image-preview").attr("style", "background-image: url(" + STREAM_URL + "images/user/" + element + ")");
                                $("#formData ." + key + "_block-text span").html("You can upload new to change current image.<br>");
                            } else {
                                $("#formData [name='" + key + "']").val(element);
                            }
                        }
                    }
                    //set id
                    $("#formData [name='id']").val(id);
                    $("#formData .password_block-text").html('Leave this blank if you don\'t want to change the password');
                    $("#formModal" + " .modal-title").html('Edit User');
                    $("#formModal" + " button.save_btn").html('Save Changes');
                    $("#formModal").modal('show');
                } else {
                    $.showToast(response.error, "error");
                }
            },
            complete: function(jqXHR, textStatus) {
                thisEl.removeClass("btn-progress");
            },
            error: function(httpRequest, textStatus, errorThrown) {
                // console.log("Error: " + textStatus + " " + errorThrown + " " + httpRequest);
                var errorMsg = textStatus + " " + errorThrown;
                $.showToast(errorMsg, 'error');
            }
        });
    }

    function clearForm() {
        var formSelector = "#formData";
        $(formSelector).trigger('reset');
        $(formSelector + " input," + formSelector + " select," + formSelector + " textarea").trigger('change');
        $(formSelector + " input," + formSelector + " select," + formSelector + " textarea").removeClass("d-block");
        $(formSelector + " input," + formSelector + " select," + formSelector + " textarea").removeAttr('disabled');
        $(formSelector + ' div.invalid-feedback').html('');
        $(formSelector + " .image-preview").removeAttr("style");
        $(formSelector + " .block-text span").html("");
        $(formSelector + " .pwindicator .label").html("");
        $(formSelector + " .pwindicator").removeClass("").addClass("pwindicator");


    }

    var paramsRequest;

    function getRowsData(params) {
        var applyBtn = $("#apply-filter");
        applyBtn.addClass("btn-progress");
        gridOptions.api.hideOverlay();

        var quickFilterVal = $('#filter-text-box').val();
        paramsRequest = params.request;
        paramsRequest['quickFilter'] = quickFilterVal;
        $.ajax({
            method: "POST",
            url: apiGrid,
            data: {
                request: JSON.stringify(paramsRequest)
            },
            dataType: 'json',
            headers: {
                api_key: API_KEY
            },
            success: function(response) {
                if (response.status) {
                    var resData = response.data;
                    gridOptions.api.setColumnDefs(resData.columns);
                    gridOptions.columnApi.setSecondaryColumns(resData.secondColumns);
                    params.successCallback(resData.rows, resData.lastRow);
                    if ((resData.rows).length == 0) gridOptions.api.showNoRowsOverlay();
                    if (goToPageGrid != null && goToPageGrid >= 0) {
                        gridOptions.api.paginationGoToPage(goToPageGrid);
                        goToPageGrid = null;
                    }
                } else {
                    params.failCallback();
                    gridOptions.api.showLoadingOverlay();
                }
            },
            complete: function(jqXHR, textStatus) {
                applyBtn.removeClass("btn-progress");
            },
            error: function(httpRequest, textStatus, errorThrown) {
                params.failCallback();
                gridOptions.api.showLoadingOverlay();
            }
        });
    }


    function getRowsDataExcel() {
        var applyBtn = $("#export-excel");
        applyBtn.addClass("btn-progress");
        $.ajax({
            method: "POST",
            url: apiGrid+"/excel",
            data: {
                request: JSON.stringify(paramsRequest),
            },
            dataType: 'json',
            headers: {
                api_key: API_KEY
            },
            success: function(response) {
                if(response==null){
                    $.showToast("Failed to export file. Not found.", 'error');
                }else{
                    window.location.href = response;
                }
            },
            complete: function(jqXHR, textStatus) {
                applyBtn.removeClass("btn-progress");
            },
            error: function(httpRequest, textStatus, errorThrown) {
                $.showToast("Failed to export file", 'error');
            }
        });
    }
</script>