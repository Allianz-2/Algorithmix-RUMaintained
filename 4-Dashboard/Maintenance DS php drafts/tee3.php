<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #5c4b8a;
            padding: 15px;
            color: white;
        }

        header strong {
            font-size: 1.5em;
        }

        .content {
            margin: 20px 0;
        }

        .filters {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .filter-group select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .stat-box {
            background-color: #E6E6FA;
            color: #5c4b8a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 23%;
        }

        .stat-box h4 {
            margin: 10px 0;
            font-size: 1.2em;
        }

        .stat-box p {
            font-size: 2em;
            font-weight: bold;
        }

        .icon {
            font-size: 40px;
            color: #5c4b8a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #5c4b8a;
            color: white;
        }

        td {
            text-align: left;
        }

        .charts {
            display: flex;
            justify-content: center;
        }

        canvas {
            width: 50% !important;
            height: auto !important;
        }
    </style>
</head>


        <!-- Chart -->
        <div class="charts">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Open', 'Resolved', 'Emergency'],
                datasets: [{
                    label: 'Maintenance Task Status',
                    data: [20, 70, 10], // Replace with dynamic data
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
</body>

</html>