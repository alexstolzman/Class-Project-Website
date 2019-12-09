<?php

//Information needed to connect to the server
$servername="sql9.freemysqlhosting.net";
$username="sql9289259";
$password="dJmcwy5wpi";
$database="sql9289259";

//Connects to a database on the given server
$db = mysqli_connect($servername,$username,$password,$database);

//Stops and outputs a message if the connection fails
if(!$db){
	die("Connection failed" . mysqli_connect_error());
}

//Assigns values to variables baed on current selected value of given menus
$console=$_POST["consoles"];
$genre=$_POST["genre"];
$year=$_POST["year"];
$rating=$_POST["rating"];

//SQL statement-returns name, release date, and game studio for games whose information matched that of the search terms
$sql="SELECT *,Studio FROM Game g
	 JOIN Game_Console gc ON g.id=gc.id 
	 JOIN Game_Genre gg ON g.id=gg.id
	 JOIN Game_Rating gr ON g.id=gr.id
	 JOIN Game_Studio gs ON g.id=gs.id
	 WHERE gc.Console='$console' 
	 	AND gg.Genre='$genre'
		AND gr.Rating='$rating'
		AND Release_Date LIKE '%$year%'";

//Sends query, returns result and stores it in an associative array
$result=mysqli_query($db,$sql);
$row=mysqli_fetch_assoc($result);

//Creates arrays for the game name, release dates, and game studios
$games=array();
$dates=array();
$studios=array();

//only runs if the query returned a result
if(!mysqli_num_rows($result)==0){
	//Adds the first game's information to relevant arrays
	array_unshift($dates, $row["Release_Date"]);
	array_unshift($games,$row["Game_Name"]);
	array_unshift($studios, $row["Studio"]);

	//Adds other games' information to relevant arrays
	while($row=mysqli_fetch_assoc($result)){
		array_push($games, $row["Game_Name"]);
		array_push($dates, $row["Release_Date"]);
		array_push($studios, $row["Studio"]);
	}

}

//Closes connection to database
mysqli_close($db);
?>



<!doctype html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<title>Search Results</title>
		<link href="results.css" type="text/css" rel="stylesheet">
	</head>
	<body>
		<h1>Results</h1>
			<!--Displays search terms used-->
			<p id="search_terms">Search Terms: <?=$console?>, <?=$genre?>, <?=$rating?>, 
			<?php
				//If they selected all years, returns "All" for the search term
				if($year=="201")
				{
			?>
				All
			<?php
				}
				//Else returns the specific year they selected for the search term
				else{
			?>
				<?=$year?>
			<?php
				}
			?>
				
				
			</p>
			
			<?php
				//Only processes if there is at least one result
				if($games){
			?>
			<table>
				<tr>
					<td class="header">Game</td>
					<td class="header">Genre</td>
					<td class="header">Platform</td>
					<td class="header">Release Date</td>
					<td class="header">Studio</td>
				</tr>
				<?php
						//goes through each game
						foreach($games as $key=>$game){
				?>
					<tr>
						<!--Puts game's information in a table row in the correct columns
							Uses $key to access game's information from the arrays-->
						<td><?=$game?></td>
						<td><?=$genre?></td>
						<td><?=$console?></td>
						<td><?=$dates[$key]?></td>
						<td><?=$studios[$key]?></td>
				
					</tr>
				<?php
					}
					}
				//Else, if there are no results, outputs No Results
				else{
					?>
					<h1 id="no_results">No Results!</h1>
				<?php
				}
				?>
				
			</table>
		
	</body>
</html>