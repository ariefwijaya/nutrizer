<script src="<?php echo base_url(); ?>assets/bundles/waypoint/jquery.waypoints.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/waypoint/shortcuts/inview.min.js"></script>
<!-- JS Libraies -->
<script src="<?php echo base_url(); ?>assets/bundles/amcharts4/core.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/amcharts4/charts.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/amcharts4/animated.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/amcharts4/sliceGrouper.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/jquery.ba-throttle-debounce.min.js"></script>

<script src="<?php echo base_url(); ?>assets/bundles/aggrid/ag-grid-enterprisev20.min.js"></script>
<script>
    "use strict";

    $(document).ready(function() {
        setChartAnalyzer();

        new Waypoint.Inview({
            element: $('.card-summary')[0],
            enter: function(direction) {
                dashboardSummary();
                this.destroy();
            }
        });

        new Waypoint.Inview({
            element: $('#chart-analyzer')[0],
            enter: function(direction) {
                chartAnalyzer();
                this.destroy();
            }
        });
        
        <?php $userInfo = getUserInfo();
			if ($userInfo != false) :
           if ($userInfo['privilege_name'] == "Administrator") :
            
			?>
        new Waypoint.Inview({
            element: $('#ag-table')[0],
            enter: function(direction) {
                $("#apply-filter").click();
                this.destroy();
            }
        });

        <?php endif; ?>
  <?php endif; ?>

        new Waypoint.Inview({
            element: $('#donutChart')[0],
            enter: function(direction) {
                setChartStreamer();
                this.destroy();
            }
        });



        $("#chart_type,#chart_time").change($.debounce(250, () => chartAnalyzer()));

        $('#filter-text-box').change($.debounce(350, function() {
            gridOptions.api.onFilterChanged();
        }));

        $("#apply-filter").click(function(e) {
            firstTimeGrid = true;
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

        $("#period-filter").click(function(e) {
            gridOptions.api.onFilterChanged();
        });

        $("#filter_type").change(function(e) {
            e.preventDefault();
            if ($(this).val() == "in_range") {
                $('#form-stream-artist-filter input[name="date_start"]').show();
                $('#form-stream-artist-filter input[name="date_end"]').show();
            } else {
                $('#form-stream-artist-filter input[name="date_start"]').show();
                $('#form-stream-artist-filter input[name="date_end"]').hide();
            }
        });

    });

    function dashboardSummary() {
        $.ajax({
            type: "POST",
            url: BASE_URL + 'api/dashboard/summary',
            dataType: 'json',
            headers: {
                api_key: API_KEY
            },
            success: function(response, status, xhr) {
                if (response.status) {
                    var data = response.data;
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        var elSelector = ".card-summary-" + element['src'] + " .card-content";
                        $(elSelector + " > h2").text(element['val_after']);
                        $(elSelector + " > p").html(element['val_res']);
                        $(elSelector + " > p").attr({
                            "data-toggle": "tooltip",
                            "title": element["val_res_format"]
                        }).tooltip();
                    }
                } else {
                    $.showToast(response.error, "error");
                }
            },
            complete: function(jqXHR, textStatus) {},
            error: function(httpRequest, textStatus, errorThrown) {
                // console.log("Error: " + textStatus + " " + errorThrown + " " + httpRequest);
                var errorMsg = textStatus + " " + errorThrown;
                $.showToast(errorMsg, 'error');
            }
        });
    }

    function chartAnalyzer() {
        var chartType = $("#chart_type").val();
        var chartTime = $("#chart_time").val();

        var chartTypeText = $("#chart_type option:selected").html();
        var chartTimeText = $("#chart_time option:selected").html();
        $.ajax({
            type: "POST",
            url: BASE_URL + 'api/dashboard/chartAnalyze',
            dataType: 'json',
            data: {
                data_type: chartType,
                time_type: chartTime
            },
            headers: {
                api_key: API_KEY
            },
            success: function(response, status, xhr) {
                if (response.status) {
                    var data = response.data;

                    //set chart
                    chartAnalyzerInstance.data = data.graph;
                    chartAnalyzerInstance.invalidateRawData();

                    var dataSummary = data.summary;
                    for (let index = 0; index < dataSummary.length; index++) {
                        const element = dataSummary[index];

                        var elSelector = ".chart-summary-" + element['data_name'];
                        if (element['data_name'] == "previous" && chartTime=="daily"){
                            chartTimeText="Yesterday";
                        }
                        else if (element['data_name'] == "current" && chartTime=="daily") {
                            chartTimeText="Today";
                        }else if (element['data_name'] == "previous" && chartTime=="monthly"){
                            chartTimeText="Last Month";
                        }
                        else if (element['data_name'] == "current" && chartTime=="monthly") {
                            chartTimeText="This Month";
                        }else if (element['data_name'] == "previous" && chartTime=="yearly"){
                            chartTimeText="Last Year";
                        }
                        else if (element['data_name'] == "current" && chartTime=="yearly") {
                            chartTimeText="This year";
                        }

                        $(elSelector + " h5").text(element['total_format']);
                        $(elSelector + " p").text(chartTimeText + " " + chartTypeText).attr({
                            "data-toggle": "tooltip",
                            "title": element["total"]
                        }).tooltip();
                    }


                    var topTitle = (chartType == "streams" ? "Top 5 Songs Streamed" : chartType == "earning" ? "Top 5 Plan Earning" : "Top 5 Plan Subscription")
                    $("#chart-analyzer-topbest h6").html("Top 5 Songs Streamed");
                    var dataTop = data.topbest;
                    for (let index = 0; index < dataTop.length; index++) {
                        const element = dataTop[index];
                        var elObject = $('#chart-analyzer-topbest ul li').eq(index);
                        elObject.find("img").attr("src", STREAM_URL + "images/song/" + element['image']);
                        elObject.find(".media-right").html(element['total_format']).attr({
                            "data-toggle": "tooltip",
                            "title": element["total"]
                        }).tooltip();
                        elObject.find(".media-title a").html(element['title']).attr({
                            "data-toggle": "tooltip",
                            "title": element["title"]
                        }).tooltip();

                        elObject.find(".text-small").html(element['sub_title']);
                    }


                    // $('#chart-analyzer-topbest ul li').each(function(i) {
                    //     $(this).find("img").attr("src",STREAM_URL+"images/song/"+)
                    // });

                } else {
                    $.showToast(response.error, "error");
                }
            },
            complete: function(jqXHR, textStatus) {},
            error: function(httpRequest, textStatus, errorThrown) {
                // console.log("Error: " + textStatus + " " + errorThrown + " " + httpRequest);
                var errorMsg = textStatus + " " + errorThrown;
                $.showToast(errorMsg, 'error');
            }
        });
    }

    var chartAnalyzerInstance;

    function setChartAnalyzer() {
        am4core.ready(function() {
            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("chart-analyzer", am4charts.XYChart);
            chartAnalyzerInstance = chart;
            chart.scrollbarY = new am4core.Scrollbar();
            chart.scrollbarY.parent = chart.leftAxesContainer;
            chart.scrollbarX = new am4core.Scrollbar();
            chart.scrollbarX.parent = chart.bottomAxesContainer;
            // Export
            chart.exporting.menu = new am4core.ExportMenu();

            // Add data
            chart.data = [];

            // Create axes
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "time_name";
            categoryAxis.renderer.grid.template.location = 0;
            // categoryAxis.renderer.minGridDistance = 30;
            // categoryAxis.tooltip.disabled = true;
            // categoryAxis.renderer.minHeight = 110;
            categoryAxis.renderer.labels.template.fill = am4core.color("#9aa0ac");
            // categoryAxis.renderer.labels.template.marginBottom = -30;
            // categoryAxis.renderer.labels.template.paddingBottom = 0;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;
            valueAxis.renderer.labels.template.fill = am4core.color("#9aa0ac");

            // Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.sequencedInterpolation = true;
            series.dataFields.valueY = "total";
            series.dataFields.categoryX = "time_name";
            series.tooltipText = "Total: [{categoryX}: bold]{valueY}[/] \n Average: {avg} ";
            series.columns.template.strokeWidth = 0;
            series.tooltip.pointerOrientation = "vertical";
            series.columns.template.column.cornerRadiusTopLeft = 10;
            series.columns.template.column.cornerRadiusTopRight = 10;
            series.columns.template.column.fillOpacity = 0.8;
            // on hover, make corner radiuses bigger
            let hoverState = series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;
            series.columns.template.adapter.add("fill", (fill, target) => {
                return chart.colors.getIndex(target.dataItem.index);
            })
            // Cursor
            chart.cursor = new am4charts.XYCursor();
        });
    }


    // let the grid know which columns and what data to use
    var goToPageGrid = null;
    var gridOptions = {
        // columnDefs: [{
        //     "headerName": "STREAM TOTAL",
        //     "field": "stream_total",
        //     "colId": "stream_total",
        //     "sort": "desc",
        //     filter:'agNumberColumnFilter',
        //     resizable: true,
        //     sortable: true,   
        //     enableRowGroup:true, 
        //     enablePivot:true,
        //     filterParams: { newRowsAction: 'keep',clearButton:true, debounceMs:1000,cellHeight:20,selectAllOnMiniFilter:true},
        // }],
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
        if (params.data != undefined && params.data.id != undefined) {
            var idVal = encodeURI(params.data.id);
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
            return '<a ' + (params.value ? 'href="' + (STREAM_URL + 'images/artist/' + valData) + '" target="_blank"' : 'href="javascript:;"') + '> <div class="small-avatar zoomhover" alt="image" style="background-image:url(' + STREAM_URL + 'thumb/artist/' + valData + ');" ></div></a>';
        } else {
            return '';
        }
    }

    var firstTimeGrid = true;

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
            url: BASE_URL + "api/dashboard/streamsArtist",
            data: {
                request: JSON.stringify(paramsRequest),
                periodfilter: $("#form-stream-artist-filter").serialize(),
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
                    if(firstTimeGrid==true){
                        gridOptions.api.onFilterChanged();
                        firstTimeGrid=false;
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
            url: BASE_URL + "api/dashboard/streamsArtist/excel",
            data: {
                request: JSON.stringify(paramsRequest),
                periodfilter: $("#form-stream-artist-filter").serialize(),
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

    function setChartStreamer() {
        am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("donutChart", am4charts.PieChart);

            // Add and configure Series
            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "total";
            pieSeries.dataFields.category = "label";

            // Let's cut a hole in our Pie chart the size of 30% the radius
            // Put a thick white border around each Slice
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 2;
            pieSeries.slices.template.strokeOpacity = 1;
            pieSeries.slices.template
                // change the cursor on hover to make it apparent the object can be interacted with
                .cursorOverStyle = [{
                    "property": "cursor",
                    "value": "pointer"
                }];


            // pieSeries.labels.template.fontSize = 10;
            // pieSeries.labels.template.paddingTop = 0;
            // pieSeries.labels.template.paddingBottom = 0;

            // pieSeries.ticks.template.disabled = true;

            // Create a base filter effect (as if it's not there) for the hover to return to
            var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
            shadow.opacity = 0;

            // Create hover state
            var hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

            // Slightly shift the shadow and make it more prominent on hover
            var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
            hoverShadow.opacity = 0.7;
            hoverShadow.blur = 5;

            // Add a legend
            chart.legend = new am4charts.Legend();
            // chart.dataSource.events.on("error", function(ev) {
            //     console.log("Oopsy! Something went wrong");
            // });
            chart.data = [];
            // {
            //     "label": "Attached",
            //     "total": 0
            // }, {
            //     "label": "Not Attached",
            //     "total": 155
            // }
            var grouper = pieSeries.plugins.push(new am4plugins_sliceGrouper.SliceGrouper());
            grouper.threshold = 0;
            grouper.groupName = "Other";
            grouper.clickBehavior = "break";


            $(".chart-streamer .card-header-action > .btn").click(function(e) {
                e.preventDefault();
                var thisEl = $(this);
                $(".chart-streamer .card-header-action > .btn").each(function(index, element) {
                    $(this).removeClass("active");
                });

                if (thisEl.hasClass("active")){
                    thisEl.addClass("active");
                    return;  
                } 

                thisEl.addClass("btn-progress");
                $.debounce(250, $.ajax({
                    method: "POST",
                    url: BASE_URL + "api/dashboard/chartStreamer",
                    data: {
                        data_type: thisEl.text()
                    },
                    dataType: 'json',
                    headers: {
                        api_key: API_KEY
                    },
                    success: function(response) {
                        if (response.status) {
                            var data = response.data;
                            chart.data = data;
                            chart.invalidateRawData();
                        } else {
                            $.showToast(response.error, 'error');
                        }
                        thisEl.addClass("active");
                    },
                    complete: function(jqXHR, textStatus) {
                        thisEl.removeClass("btn-progress");
                    },
                    error: function(httpRequest, textStatus, errorThrown) {
                        var errorMsg = textStatus + " " + errorThrown;
                        $.showToast(errorMsg, 'error');
                    }
                }));
            });

            $(".chart-streamer .card-header-action > .btn").eq(0).trigger('click');
        }); // end am4core.ready()
    }
</script>