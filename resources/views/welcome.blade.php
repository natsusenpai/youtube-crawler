<!doctype html>
<html lang="{{ app()->getLocale() }}">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
    <script src="https://unpkg.com/axios@0.18.0/dist/axios.min.js"></script>
    <div style="position: relative; height:15vh; width:80vw">
        <label>Total Subcribers:</label>
        <h1 id="numberOfView">Loading...</h1>
    </div>
    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
        <canvas id="myChart"></canvas>
    </div>
    
    <script>
    var ctx = document.getElementById("myChart").getContext('2d');
    var data = [], labels = [];
    

    axios.get('/dashboard?channelId=UCSJ4gkVC6NrvII8umztf0Ow')
        .then(function (response) {
            document.getElementById("numberOfView").innerHTML = response.data.chartData[0].total_subcribers;
            response.data.chartData.forEach((item)=>{
                var date = new Date(item.unixtime*1000);
                data.push(item.total_subcribers);
                labels.push(`Time: ${date}`);    
            })
            
            var purple_orange_gradient = ctx.createLinearGradient(0, 0, 0, 600);
            purple_orange_gradient.addColorStop(0, 'rgba(255, 227, 166, 1)');
            purple_orange_gradient.addColorStop(1, 'rgba(255, 242, 213, 1)');

            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels.reverse(),
                    datasets: [{
                        // fillColor : gradient, // Put the gradient here as a fill color
                        // strokeColor : "#ff6c23",
                        // pointColor : "#fff",
                        // pointStrokeColor : "#ff6c23",
                        // pointHighlightFill: "#fff",
                        // pointHighlightStroke: "#ff6c23",
                        label: '# of subscriber',
                        data: data.reverse(),
                        backgroundColor: purple_orange_gradient,
                        borderColor: [
                            'rgba(255, 204, 94, 1)',
                            'rgba(255, 204, 94, 1)',
                            'rgba(255, 204, 94, 1)',
                        ],
                        borderWidth: 3
                    }]
                },
                options: {
                    scales: {
                        xAxes: [{
                            display: false
                        }],
                        yAxes: [{
                            display: false,
                            ticks: {
                                beginAtZero: false
                            }
                        }]
                    }
                }
            });
            
            
        })
        .catch(function (error) {
            console.log(error);
        });
    
    </script>
</html>
