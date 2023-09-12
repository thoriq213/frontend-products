<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <title>Report Products</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
</head>

<body>
    <div class="container-fluid p-5">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">select area</label>
                    <select class="form-control" id="area_id" name="area_id[]" multiple='multiple'>
                        <option value="option1">Option 1</option>
                        <option value="option2">Option 2</option>
                        <option value="option3">Option 3</option>
                        <option value="option4">Option 4</option>
                        <option value="option5">Option 5</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">from date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">to date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control">
                </div>
            </div>
        </div>

        <canvas id="barChart" width="400" height="200" class="my-5"></canvas>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>Larry</td>
                    <td>the Bird</td>
                    <td>@twitter</td>
                </tr>
            </tbody>
        </table>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        <script>
            // Data for the chart (you can fetch this data from your database)
            let label_chart = ['Label 1', 'Label 2', 'Label 3'];
            let data_chart = [5, 10, 15];

            chart(label_chart, data_chart);

            function chart(label, data) {
                Chart.register(ChartDataLabels);
                var data = {
                    labels: label,
                    datasets: [{
                        label: 'nilai',
                        data: data,
                        backgroundColor: [
                            'rgba(1, 1, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                };

                // Get the canvas element
                var ctx = document.getElementById('barChart').getContext('2d');

                // Create the bar chart
                var barChart = new Chart(ctx, {
                    showTooltips: false,
                    type: 'bar',
                    data: data,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100, // Set the y-axis maximum to 100 for percentages
                            }
                        },
                        plugins: {
                            datalabels: { // This code is used to display data values
                                anchor: 'end',
                                color: 'black',
                                align: 'top',
                                formatter: Math.round,
                                font: {
                                    weight: 'bold',
                                    size: 16,
                                }
                            },
                        }
                    },
                });
            }
        </script>
        <script>
            $(document).ready(function() {
                $("#area_id").select2({});
                $('#from_date, #to_date, #area_id').change(function() {
                    let area_id = $('#area_id').val();
                    let from_date = $('#from_date').val();
                    let to_date = $('#to_date').val();

                    getDataChart(area_id, from_date, to_date);
                    getDataTable(area_id, from_date, to_date);
                });
            });

            function getDataChart(area_id, from_date, to_date) {
                let formData = {
                    'area_id': area_id,
                    'from_date': from_date,
                    'to_date': to_date
                }

                $.ajax({
                    url: 'http://localhost/frontend-products/products/get_data_chart', // Replace with your API endpoint
                    type: 'POST',
                    data: formData,
                    dataType: 'json', // The expected data type of the response
                    success: function(data) {
                        // Handle the response data here
                        console.log(data);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        console.error(status, error);
                    }
                });
            }

            function getDataTable(area_id, from_date, to_date) {
                let formData = {
                    'area_id': area_id,
                    'from_date': from_date,
                    'to_date': to_date
                }

                $.ajax({
                    url: 'http://localhost/frontend-products/products/get_data_table', // Replace with your API endpoint
                    type: 'POST',
                    data: formData,
                    dataType: 'json', // The expected data type of the response
                    success: function(data) {
                        // Handle the response data here
                        console.log(data);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        console.error(status, error);
                    }
                });
            }
        </script>
</body>

</html>