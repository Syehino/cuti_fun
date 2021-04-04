<?php 
	include 'env.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<title>Cuti</title>

<!--CSS style sheets are here-->
<link rel="stylesheet" href="css/main.css" />
</head>
<body>

<!-- Side navigation -->
<div id="mySidenav" class="sidenav">
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<a href="#">Tambah Cuti</a>
	<a href="#">Laporan</a>
  </div>
  
  <span style="font-size:25px;cursor:pointer" onclick="openNav()">&#9776;</span>
  

	<header>
		<h1>Cuti</h1>
	</header>
	<main>
	<form action="insert.php" method="post">
		<table id ="leaveTab">
			<thead>
				<tr>
					<th>Tahun</th>
					<th>Cuti Tahun Lepas</th>
					<th>Cuti Layak</th>
					<th>Jumlah Cuti Layak</th>
					<th>Jumlah Cuti Diambil</th>
					<th>Baki Cuti</th>
					<th>Cuti Bawa Ke Hadapan</th>
					<th>Cuti Bagi GCR</th>
					<th>Jumlah GCR</th>
					<th>Cuti Luput</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$sql = "SELECT * FROM cuti_hakim";
				$result = $conn->query($sql);

				if($result = mysqli_query($conn, $sql)){
					if(mysqli_num_rows($result) > 0){
						while($row = mysqli_fetch_array($result)){
							echo "<tr>";
								echo "<td id = 'year'>" . $row['year'] . "</td>";
								echo "<td id = 'prevLeave'>" . $row['last_year_leave'] . "</td>";
								echo "<td id = 'allowedLeave'>" . $row['allowed_leave'] . "</td>";
								echo "<td id = 'ttlAllowedLeave'>" . $row['total_allowed_leave'] . "</td>";
								echo "<td id = 'ttlLeaveTaken'>" . $row['total_leave_taken'] . "</td>";
								echo "<td id = 'leaveBal'>" . $row['leave_balance'] . "</td>";
								echo "<td id = 'carriedLeave'>" . $row['carried_leave'] . "</td>";
								echo "<td id = 'gcrLeave'>" . $row['gcr_leave'] . "</td>";
								echo "<td id = 'gcrTtl'>" . $row['gcr_total'] . "</td>";
								echo "<td id = 'expiredLeave'>" . $row['expired_leave'] . "</td>";
							echo "</tr>";
						}
						echo "</table>";
						// Free result set
						mysqli_free_result($result);
					} else{
						echo "No records matching your query were found.";
					}
				} else{
					echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
				}
				$conn->close();
			?>
			</tbody>
			<p>
     			   <input type="button" id="addRow" value="Tambah Tahun Depan" onclick="addRow()" /></p>
				<input type="submit" value="Submit"></form>
		</table>

		
	</main>

	<script
			  src="https://code.jquery.com/jquery-3.6.0.js"
			  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
			  crossorigin="anonymous"></script>
	
	<script>

			var lastRowArr = [];
			var last = $("#leaveTab").find("tr").last();

			var $row = last      // Finds the closest row <tr> 
			$tds = $row.find("td");             // Finds all children <td> elements

			$.each($tds, function() {               // Visits every single <td> element
				/* console.log($(this).text()); */        // Prints out the text within the <td>	
				lastRowArr.push($(this).text());
			});

		function nextYear() {

			var arrCurrYear = lastRowArr.map(Number);
			var nextRow = [];

			var nextYear = arrCurrYear[0] + 1;
			var pastYearLeave = arrCurrYear[5];
			var allowedLeave = arrCurrYear[2];
			var ttlAllowedLeave = arrCurrYear[2] + arrCurrYear[5];
			var ttlLeaveTaken = 0;
			var leaveBal = arrCurrYear[2] + arrCurrYear[5];;
			var nextYearLeave = arrCurrYear[2] + arrCurrYear[5];;
			var gcrLeave = 0;
			var gcrTotal = arrCurrYear[8];
			var expLeave = 0;

			nextRow.push(nextYear,pastYearLeave,allowedLeave,ttlAllowedLeave,ttlLeaveTaken,leaveBal,nextYearLeave,gcrLeave,gcrTotal,expLeave);

			return nextRow;
		}

		var nextYearRow = nextYear();

		function openNav() {
		  document.getElementById("mySidenav").style.width = "250px";
		}
	
		function closeNav() {
		  document.getElementById("mySidenav").style.width = "0"; 
		}

		function addRow() {
		var button = $('#addRow');

		var arrHead = new Array();
   		arrHead = ['Tahun','Cuti Tahun Lepas','Cuti Layak','Jumlah Cuti Layak','Jumlah Cuti Diambil','Baki Cuti','Cuti Bawa Ke Hadapan','Cuti Bagi GCR','Jumlah GCR','Cuti Luput']; // table headers.
		arrid = ['yearLast','prevLeaveLast','allowedLeaveLast','ttlAllowedLeaveLast','ttlLeaveTakenLast','leaveBalLast','carriedLeaveLast', 'gcrLeaveLast','gcrTtlLast','expiredLeaveLast']
									 
        var leaveTab = document.getElementById('leaveTab');

        var rowCnt = leaveTab.rows.length;    // get the number of rows.
        var tr = leaveTab.insertRow(rowCnt); // table row.
        tr = leaveTab.insertRow(rowCnt);

        for (var c = 0; c < arrHead.length; c++) {
            var td = document.createElement('td'); 
            td = tr.insertCell(c);

            var ele = document.createElement('input');
            ele.setAttribute('type', 'text');
			ele.setAttribute('id', arrid[c]);
            ele.setAttribute('value', nextYearRow[c]);
			ele.setAttribute('name', arrid[c]);

            td.appendChild(ele);   
        }
		$(button).prop('disabled', true);
	}
	
	$(document).on('keyup', '#ttlLeaveTakenLast', function(){
		var prevLeavBal  = Number($('#prevLeaveLast').val());
		var allowedLeaveLast  = Number($('#allowedLeaveLast').val());
		var ttlLeaveBal = prevLeavBal + allowedLeaveLast;
		var ttlLeaveTaken  = Number($('#ttlLeaveTakenLast').val());
		console.log(ttlLeaveTaken);

		document.getElementById('leaveBalLast').value =  ttlLeaveBal - ttlLeaveTaken ;
	});

	$(document).on('keyup', '#gcrLeaveLast', function(){
		var gcrLeave  = Number($('#gcrLeaveLast').val());
		var prevGCRLeave = Number($('#gcrTtl').last().text());
		var ttlGCRLeave = gcrLeave + prevGCRLeave;
		
		document.getElementById('gcrTtlLast').value =  ttlGCRLeave;
		
	});
	
	$(document).on('keyup', '#gcrLeaveLast', function(){
		var gcrLeave  = Number($('#gcrLeaveLast').val());
		var prevGCRLeave = Number($('#gcrTtl').last().text());
		var ttlGCRLeave = gcrLeave + prevGCRLeave;
		
		document.getElementById('gcrTtlLast').value =  ttlGCRLeave;
		
	});
	
	$(document).on('focus', '#leaveBalLast', function(){
		var prevLeavBal  = Number($('#prevLeaveLast').val());
		var allowedLeaveLast  = Number($('#allowedLeaveLast').val());
		var ttlLeaveBal = prevLeavBal + allowedLeaveLast;

		var ttlLeaveTaken  = Number($('#ttlLeaveTakenLast').val());
		var gcr  = Number($('#gcrLeaveLast').val());
		
		document.getElementById('leaveBalLast').value = ttlLeaveBal - gcr - ttlLeaveTaken;
		document.getElementById('carriedLeaveLast').value = ttlLeaveBal - gcr - ttlLeaveTaken;
		
	});
	
	$(document).on('focus', '#expiredLeaveLast', function(){
		var prevLeavBal  = Number($('#leaveBalLast').val());

		if(prevLeavBal > 70)
		{
			var expiredLeave = prevLeavBal - 70;

			document.getElementById('expiredLeaveLast').value = expiredLeave;
		}
		
	});	
	
		</script>
</body>
</html>