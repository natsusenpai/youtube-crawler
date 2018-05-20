<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <!-- libraries -->
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" integrity="sha256-BJ/G+e+y7bQdrYkS2RBTyNfBHpA9IuGaPmf9htub5MQ=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
  
  <!-- actual layout -->
  <div class="bg-light">
    <div class="container rounded-0 bg-white">
      <div class="row border-bottom"> 
        <div class="col-1">
          <div class="dropdown float-sm-right  d-inline-flex p-2">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"></button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </div>
        </div>
        <div class="col-2">
          <a href="#" class="float-sm-left">
            <img src="{{ asset('images/youtube_logo.png') }}" href="#" class="img-fluid  d-inline-flex p-2">
          </a>
        </div>
        <div class="col">
          <div class="float-sm-right d-inline-flex p-2">
            <button href="#" class="btn btn-light border rounded col">
              View detail
            </button>
            <a href="#">
              <img src="{{ asset('images/link-chain.png') }}" class="img-fluid p-2" style="max-height:40px;" >
            </a>
          </div>
        </div>
      </div>
      <div class="row">
        
        <div class="col-2 border-right d-flex align-items-center justify-content-center">
          <div>  
            <span class="p-2 text-secondary">Total Videos</span><br>
            <h1 id="numberVideos" class="p-2"></h1>
          </div>
          
        </div>

        <div class="col">
          <div class="row">

            <div class="col">
              <div class="chart-container">
                <div class="row">
                  <span class="text-secondary">Total Views</span>
                </div>
                <div class="row">
                  <h1 id="numberViews" class="float-sm-left text-secondary">
                    loading...
                  </h1>
                  <div class="p-2">
                    <i id="viewChartArrow"></i>
                    <span id="diffView">
                      loading...
                    </span>
                  </div>
                  
                </div>
                <canvas id="viewChart"></canvas>
              </div>              
            </div>

            <div class="col">
              <div class="chart-container" >
                <div class="row">
                  <span class="text-secondary">Total Likes</span>
                </div>
                <div class="row">
                  <h1 id="numberLikes" class="float-sm-left text-secondary">
                    loading...
                  </h1>
                  <div class="p-2">
                    <i id="likeChartArrow"></i>
                    <span id="diffLike">
                      loading...
                    </span>
                  </div>
                </div>
                <canvas id="likeChart"></canvas>
              </div>
            </div>
            <div class="w-100"></div>
            <div class="col">
              <div class="chart-container" >
                <div class="row">
                  <span class="text-secondary">Total Subcribers</span>
                </div>
                <div class="row">
                  <h1 id="numberSubcribers" class="float-sm-left text-secondary">
                    loading...
                  </h1>
                  <div class="p-2">
                    <i id="subcriberChartArrow"></i>
                    <span id="diffSubcriber">
                      loading...
                    </span>
                  </div>
                </div>
                <canvas id="subcriberChart"></canvas>
              </div>
            </div>
            <div class="col"></div>
          </div>
        </div>
      </div> 
    </div>

  </div>
    
  <!-- chartjs rendering -->
  <script>
    let subcriberChartData = {},
        viewChartData = {},
        likeChartData = {};
    $.get('/dashboardSummary?channelId={{ app('request')->input('channelId') }}')
    .then(function (response) {
      $('#numberVideos').html(convertNumber(response.chartData[0].totalVideos));
      $('#numberViews').html(convertNumber(response.chartData[0].totalViews));
      $('#numberSubcribers').html(convertNumber(response.chartData[0].totalSubcribers));
      $('#numberLikes').html(convertNumber(response.chartData[0].totalLikes));
      $('#diffSubcriber').html(convertPercent(response.chartData[0].totalSubcribers, response.diffSubcriber));
      $('#diffLike').html(convertNumber(response.diffLike));
      $('#diffView').html(convertNumber(response.diffView));

      if (response.diffSubcriber > 0) {
        $('#subcriberChartArrow').addClass('fas fa-arrow-up text-success');
        $('#diffSubcriber').addClass('text-success');
      } 
      else if (response.diffSubcriber < 0) {
        $('#subcriberChartArrow').addClass('fas fa-arrow-down text-danger');
        $('#diffSubcriber').addClass('text-danger');
      }

      if (response.diffView > 0) {
        $('#viewChartArrow').addClass('fas fa-arrow-up text-success');
        $('#diffView').addClass('text-success');
      } 
      else if (response.diffView < 0) {
        $('#viewChartArrow').addClass('fas fa-arrow-down text-danger');
        $('#diffView').addClass('text-danger');
      }

      if (response.diffLike > 0) {
        $('#likeChartArrow').addClass('fas fa-arrow-up text-success');
        $('#diffLike').addClass('text-success');
      } 
      else if (response.diffLike < 0) {
        $('#likeChartArrow').addClass('fas fa-arrow-down text-danger');
        $('#diffLike').addClass('text-danger');
      }
      
      

      timestampLabels = response.chartData.map(item => `Time: ${new Date(item.unixtime*1000)}`);
      subcriberChartData.labels = timestampLabels;
      viewChartData.labels = timestampLabels;
      likeChartData.labels = timestampLabels;
      subcriberChartData.data = response.chartData.map(item => item.totalSubcribers);
      viewChartData.data = response.chartData.map(item => item.totalViews);
      likeChartData.data = response.chartData.map(item => item.totalLabels);
    
      renderSubcriberChart("subcriberChart", subcriberChartData);
      renderViewChart("viewChart", viewChartData);
      renderLikeChart("likeChart", subcriberChartData);
    })
    .catch(function (error) {
      console.log(error);
    });
      
    function renderSubcriberChart(documentId, data) {
      let ctx = document.getElementById(documentId).getContext('2d');
      let orange_white_gradient = ctx.createLinearGradient(0, 0, 0, 600);
      orange_white_gradient.addColorStop(0, 'rgba(255, 227, 166, 1)');
      orange_white_gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

      let subcriberChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [{
            // label: '# of subcriber',
            data: data.data,
            backgroundColor: orange_white_gradient,
            borderColor: [
              'rgba(255, 204, 94, 1)'
            ],
            borderWidth: 2
          }]
        },
        options: {
          legend: {
            display: false
          },
          tooltips: {
            callbacks: {
              label: function(tooltipItem) {
                return tooltipItem.yLabel;
              }
            }
          },
          scales: {
            xAxes: [{
              display: false
            }],
            yAxes: [{
              display: false,
              ticks: {
                  beginAtZero: false,
                  maxTicksLimit:2
              }
            }]
          }
        }
      });   
    }

    function renderViewChart(documentId, data) {
      let ctx = document.getElementById(documentId).getContext('2d');
      let orange_white_gradient = ctx.createLinearGradient(0, 0, 0, 600);
      orange_white_gradient.addColorStop(0, 'rgba(255, 227, 166, 1)');
      orange_white_gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

      let subcriberChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [{
            // label: '# of subcriber',
            data: data.data,
            backgroundColor: orange_white_gradient,
            borderColor: [
              'rgba(255, 204, 94, 1)'
            ],
            borderWidth: 2
          }]
        },
        options: {
          legend: {
            display: false
          },
          tooltips: {
            callbacks: {
              label: function(tooltipItem) {
                return tooltipItem.yLabel;
              }
            }
          },
          scales: {
            xAxes: [{
              display: false
            }],
            yAxes: [{
              display: false,
              ticks: {
                  beginAtZero: false,
                  maxTicksLimit:2
              }
            }]
          }
        }
      });   
    }

    function renderLikeChart(documentId, data) {
      let ctx = document.getElementById(documentId).getContext('2d');
      let orange_white_gradient = ctx.createLinearGradient(0, 0, 0, 600);
      orange_white_gradient.addColorStop(0, 'rgba(255, 227, 166, 1)');
      orange_white_gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

      let subcriberChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [{
            // label: '# of subcriber',
            data: data.data,
            backgroundColor: orange_white_gradient,
            borderColor: [
              'rgba(255, 204, 94, 1)'
            ],
            borderWidth: 2
          }]
        },
        options: {
          legend: {
            display: false
          },
          tooltips: {
            callbacks: {
              label: function(tooltipItem) {
                return tooltipItem.yLabel;
              }
            }
          },
          scales: {
            xAxes: [{
              display: false
            }],
            yAxes: [{
              display: false,
              ticks: {
                  beginAtZero: false,
                  maxTicksLimit:2
              }
            }]
          }
        }
      });   
    }
    
    function convertNumber(number, unit=1000000000) {
      if (number == 0) return number;
      const dict = {
        "1" : "",
        "1000" : "K",
        "1000000" : "M",
        "1000000000" : "B",
      };
      roundDiv = Math.floor(Math.abs(number)/unit);
      if (roundDiv) {
        return `${roundDiv}${dict[unit]}`;
      } 
      else {
        return convertNumber(number, Math.floor(unit/1000));
      }
    }

    function convertPercent(total, diff) {
      if (total == diff) return 0;
      return `${(diff*100/total).toFixed(2)}%`;
    }

      
  </script>
</html>
