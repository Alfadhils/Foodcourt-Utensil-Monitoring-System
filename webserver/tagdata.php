<?php 
		include 'connection.php';
		?>
<html>
<head>
	<title>Utensil Tag Data</title>
	<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
    <table class="data-table">
        <caption class="title">Utensil Tag Data</caption>
        <thead>
            <tr>
                <th>No</th>
                <th>Tag</th>
                <th>Action</th>
				<th>Status</th>
            </tr>
        </thead>
        <tbody>
		<?php
			$no  = 1;
			$sql = mysqli_query($koneksi, "SELECT No, tag, CASE WHEN status = 'available' THEN 'TERSEDIA' ELSE 'DIGUNAKAN' END AS status FROM availability");

			while ($row = mysqli_fetch_array($sql)) {
				echo "<tr>
						<td style='text-align: center;'>".$row['No']."</td>
						<td style='text-align: center;'>".$row['tag']."</td>
						<td style='text-align: center;'>
							<a href='tagdelete.php?No=$row[No]'>Hapus</a>
						</td>
						<td style='text-align: center;'>".$row['status']."</td>
					</tr>";
				$no++;
			}
		?>
        </tbody>
    </table>
</body>
</html>