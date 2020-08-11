<div class="section-body">
  <div class="row card-summary">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 card-summary-users">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Users</h5>
                  <h2 class="mb-3 font-18">&hellip;</h2>
                  <p class="mb-0"><span class="col-green">10%</span> Increase</p>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                <div class="banner-img">
                  <img src="assets/img/banner/1.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 card-summary-songs">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Songs</h5>
                  <h2 class="mb-3 font-18">&hellip;</h2>
                  <p class="mb-0"><span class="col-orange">09%</span> Decrease</p>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                <div class="banner-img">
                  <img src="assets/img/banner/2.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 card-summary-artists">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Artists</h5>
                  <h2 class="mb-3 font-18">&hellip;</h2>
                  <p class="mb-0"><span class="col-green">18%</span>Increase</p>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                <div class="banner-img">
                  <img src="assets/img/banner/3.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 card-summary-streams">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Streams Total</h5>
                  <h2 class="mb-3 font-18">&hellip;</h2>
                  <p class="mb-0"><span class="col-green">42%</span> Increase</p>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                <div class="banner-img">
                  <img src="assets/img/banner/4.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-sm-12 col-lg-12">
      <div class="card ">
        <div class="card-header">
          <h4>Chart Analyzer</h4>
          <div class="card-header-form">
            <!-- <form> -->
            <select id="chart_type" class="form-control">
              <option value="streams">Streams</option>
              <!-- <option value="earning" disabled>Earning</option>
              <option value="subscription" disabled>Subscription</option> -->
            </select>
            <!-- </form> -->
          </div>
          <div class="card-header-form">
            <!-- <form> -->
            <select id="chart_time" class="form-control">
              <option value="daily">Daily</option>
              <option value="monthly">Monthly</option>
              <option value="yearly">Yearly</option>
            </select>
            <!-- </form> -->
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-9">
              <div id="chart-analyzer-highlight" class="row mb-0">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 chart-summary-avg">
                  <div class="list-inline text-center">
                    <div class="list-inline-item p-r-30">
                      <!-- <i data-feather="bar-chart-2" class="col-blue-grey"></i> -->
                      <h5 class="m-b-0">&hellip;</h5>
                      <p class="text-muted font-14 m-b-0">Daily Streams</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 chart-summary-previous">
                  <div class="list-inline text-center">
                    <div class="list-inline-item p-r-30">
                      <!-- <i data-feather="crosshair" class="col-blue-grey"></i> -->
                      <h5 class="m-b-0">&hellip;</h5>
                      <p class="text-muted font-14 m-b-0">Yesterday Streams</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 chart-summary-current">
                  <div class="list-inline text-center">
                    <div class="list-inline-item p-r-30">
                      <!-- <i data-feather="crosshair" class="col-blue-grey"></i> -->
                      <h5 class="mb-0 m-b-0">&hellip;</h5>
                      <p class="text-muted font-14 m-b-0">Today Streams</p>
                    </div>
                  </div>
                </div>
              </div>
              <div id="chart-analyzer" class="chartdiv"></div>
            </div>
            <div class="col-lg-3">
              <div class="summary-item" id="chart-analyzer-topbest">
                <h6 class="mt-3">Top 5 Songs Streamed<span class="text-muted"></span></h6>
                <ul class="list-unstyled list-unstyled-border">
                  <?php for ($i = 0; $i < 5; $i++) : ?>
                    <li class="media">
                      <img alt="image" src="<?php echo STREAM_URL; ?>images/song/e6c32a351322ea7689ad85e94fc5b0f1.jpg" class="mr-3 user-img-radious-style user-list-img" width="40" height="40">
                      <div class="media-body">
                        <div class="media-right">&hellip;</div>
                        <div class="media-title"><a href="javascript:;">{Song Title}</a></div>
                        <div class="text-small text-muted">{Song Artist}
                        </div>
                      </div>
                    </li>
                  <?php endfor; ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

	<?php $userInfo = getUserInfo();
			if ($userInfo != false) :
           if ($userInfo['privilege_name'] == "Administrator") :
            
			?>
  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Streams per Artist</h4>
          <div class="card-header-form">
            <form onsubmit="return false;">
              <div class="input-group">
                <input id="filter-text-box" type="text" class="form-control" placeholder="Search">
                <div class="input-group-btn">
                  <button id="apply-filter" type="button" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="card-body">
          <form id="form-stream-artist-filter">
            <div class="row">
              <div class="col-12 col-md-6 col-lg-2">
                <select id="filter_type" class="form-control form-control-sm" name="filter_type">
                  <option value="equal">Equal</option>
                  <option value="greater_than">Greater Than</option>
                  <option value="equal_greater_than">Equal or Greater Than</option>
                  <option value="less_than">Less Than</option>
                  <option value="equal_less_than">Equal or Less Than</option>
                  <option value="not_equal">Not Equal</option>
                  <option value="in_range">In Range</option>
                </select>
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <input  name="date_start" type="date" class="form-control col-sm-12" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <input name="date_end" type="date" class="form-control col-sm-12" style="display:none"/>
              </div>
              <div class="col-12 col-md-6 col-lg-2">
                <button id="period-filter" type="button" class="form-control btn btn-primary">Filter</button>
              </div>
              <div class="col-12 col-md-6 col-lg-2">
                <button id="export-excel" type="button" class="form-control btn btn-success">Excel</button>
              </div>
            </div>
          </form>
          <div style="width:100%; height: 350px;" class="p-t-10">
            <div id="ag-table" style="height: 100%; width: 100%; box-sizing: border-box;" class="ag-theme-balham"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

    <?php endif; ?>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-12 col-sm-12 chart-streamer">
      <div class="card mt-sm-5 mt-md-0">
        <div class="card-header">
          <h4>Streamers</h4>
          <div class="card-header-action">
            <button class="btn ">Devices</button>
            <button class="btn">Browser</button>
          </div>
        </div>
        <div class="card-body">
          <div id="donutChart" class="chartdiv-donut"></div>
          <!-- <ul class="p-t-30 list-unstyled">
            <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-black"></i></span>Search Engines<span class="float-right">30%</span></li>
            <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-green"></i></span>Direct Click<span class="float-right">50%</span></li>
            <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-orange"></i></span>Video Click<span class="float-right">20%</span></li>
          </ul> -->
        </div>
      </div>
    </div>
  </div>

</div>