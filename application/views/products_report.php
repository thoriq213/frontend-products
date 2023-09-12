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
                        <?php foreach ($area as $key => $val) { ?>

                            <option value="<?= $val['area_id'] ?>"><?= $val['area_name'] ?></option>

                        <?php } ?>
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
        <div class="canvas">
            <canvas id="barChart" width="400" height="200" class="my-5"></canvas>
        </div>
        <div class="table">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>Brand</td>
                        <?php foreach ($data_table as $key_table => $val) { ?>
                            <td><?= $key_table ?></td>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($brand as $key => $val) { ?>
                        <tr>
                            <td><?= $val['brand_name'] ?></td>
                            <?php foreach ($data_table as $key_table) { ?>
                                <?php foreach ($key_table as $index => $val_detail) { ?>
                                    <?php if ($val_detail['brand_id'] == $val['brand_id']) { ?>
                                        <td><?= (int)$val_detail['percentage'] . '%' ?></td>
                                <?php }
                                } ?>
                            <?php } ?>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        <script>
            // Data for the chart (you can fetch this data from your database)
            let data = <?= json_encode($data_chart) ?>;

            let label_chart = data.map(item => item.area_name);
            let data_chart = data.map(item => item.percentage);

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
                        let label_chart = $.map(data, item => item.area_name);
                        let data_chart = $.map(data, item => item.percentage);

                        $("canvas#barChart").remove();

                        $("div.canvas").append('<canvas id="barChart" width="400" height="200" class="my-5"></canvas>');

                        chart(label_chart, data_chart);
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
                        let header = '';
                        let brand = '';
                        let percentage = '';
                        let table_field = '';
                        let all_brand = <?= json_encode($brand) ?>;
                        $.each(data, function(index, item){
                             header += '<td>' + index + '</td>';
                        });

                        $.each(all_brand, function(index, item){
                            brand += '<td>' + item.brand_name + '</td>'
                            $.each(data, function(index_area){
                                $.each(data[index_area], function(index_detail, item_detail){
                                    if(item.brand_id == item_detail.brand_id){
                                        percentage += '<td>' + parseInt(item_detail.percentage) +'%</td>'
                                    }
                                })
                            })
                            table_field += '<tr>' + brand + percentage + '</tr>';
                            brand = '';
                            percentage = '';
                        });

                    const row = `<table class="table table-striped">
                <thead>
                    <tr>
                        <td>Brand</td>
                        ${header}
                    </tr>
                </thead>
                <tbody>
                        ${table_field}
                </tbody>
            </table>`
            console.log(row);
            $('.table').html(row);
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