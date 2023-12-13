<?php 
		include 'connection.php';
		?>
<html>
<head>
	<title>Result Graph</title>
	<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<style type="text/css">
		body {
			font-size: 15px;
			color: #343d44;
			font-family: "segoe-ui", "open-sans", tahoma, arial;
			padding: 0;
			margin: 0;
		}
		table {
			margin: auto;
			font-family: "Lucida Sans Unicode", "Lucida Grande", "Segoe Ui";
			font-size: 12px;
		}

		h1 {
			font-family: "Lato Black";
		}
		h2 {
			font-family: "Lato Black";
		}

		table td {
			transition: all .5s;
		}
		
		.data-table {
			border-collapse: collapse;
			font-size: 14px;
			min-width: 537px;
		}

		.data-table th, 
		.data-table td {
			border: 1px solid #e1edff;
			padding: 7px 17px;
		}
		.data-table caption {
			margin: 7px;
		}

		.data-table thead th {
			background-color: #508abb;
			color: #FFFFFF;
			border-color: #6ea1cc !important;
			text-transform: uppercase;
		}

		.data-table tbody td {
			color: #353535;
		}
		.data-table tbody td:first-child,
		.data-table tbody td:nth-child(4),
		.data-table tbody td:last-child {
			text-align: right;
		}

		.data-table tbody tr:nth-child(odd) td {
			background-color: #f4fbff;
		}
		.data-table tbody tr:hover td {
			background-color: #ffffa2;
			border-color: #ffff0f;
		}

		.data-table tfoot th {
			background-color: #e5f5ff;
			text-align: right;
		}
		.data-table tfoot th:first-child {
			text-align: left;
		}
		.data-table tbody td:empty
		{
			background-color: #ffcccc;
		}
	</style>
</head>
<body>
	<center>
		<h1>FOODCOURT UTENSIL</h1>
		<h2>MONITORING SYSTEM</h2>
	</center>
	<div class="w3-bar w3-white w3-wide w3-padding w3-card">
		<a href='index.html' class="w3-bar-item w3-button"><b>BY </b> GROUP 2.11</a>
		<div class="w3-right w3-hide-small">
			<a href='register.php' class="w3-bar-item w3-button">Register Data</a>
			<a href='monitoring.php' class="w3-bar-item w3-button">Monitoring</a>
			<a href='tagdata.php' class="w3-bar-item w3-button">Database</a>
			<a href='graphviz.php' class="w3-bar-item w3-button">Result Graph</a>
		</div>
	</div>
    <div style="text-align: center;">
        <?php
            if (isset($_POST['selected_date'])) {
				// Sanitize and validate the input date
				$selectedDate = date('Y-m-d', strtotime($_POST['selected_date']));
			} else {
				// Default to today's date if no date is submitted
				$selectedDate = date('Y-m-d');
			}
            $hours = [];
            $entryCount = [];
            $query = "SELECT
                            HOUR(in_time) as hour_intime,
                            COUNT(*) AS entry_count
                        FROM
                            logging 
                        WHERE
                            DATE(date) = '$selectedDate'
                        GROUP BY hour_intime";

            $result = mysqli_query($koneksi, $query);

            if (!$result) {
                die("Error in query: " . mysqli_error($koneksi));
            }

            while ($row = mysqli_fetch_array($result)) {
                $hours[] = $row['hour_intime'];
                $entryCount[] = $row['entry_count'];
            }

            $completeData = array_map('intval', array_fill(0, 24, 0));
            foreach ($hours as $index => $hour) {
                $completeData[$hour] = intval($entryCount[$index]);
            }
        ?>
		<form method="post" action="" style="margin-top: 20px; text-align: center;">
			<label for="selected_date" style="font-size: 18px; color: #333; margin-right: 10px;">Select a Date:</label>
			<input type="date" id="selected_date" name="selected_date" value="<?php echo $selectedDate; ?>" style="padding: 8px; font-size: 16px;" required>
			<button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 15px; font-size: 16px; border: none; cursor: pointer;">Generate Chart</button>
		</form>
        <canvas id="myChart" width="350" height="100"></canvas>
        <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Array.from({length: 24}, (_, i) => i),
                    datasets: [{
                        label: 'Entry Count',
                        data: <?= json_encode($completeData) ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom'
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>
</body>
</html>