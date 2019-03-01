<?php
// Makes a Database Connection
 include "databaseConnection.php";
 
// Puts something in the database with comment 
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(isset($_POST['register']))
	{
		if(empty($_POST['textArea']))
		{
			
		}
		else
		{
			$textArea = $_POST['textArea'];
			$heroId = $_POST['heroId'];
			$heroDate = date("Y-m-d @ h:i:sa");
			$postComment = "INSERT INTO rating(ratingId, heroId, ratingDate, ratingReview) VALUES(NULL, '$heroId', '$heroDate', '$textArea');";
			mysqli_query($connection, $postComment);
		}
	}
}

if(isset($_GET['teamId']))
{
    $teamId = $_GET['teamId'];
}
else
{
    $teamId = 0;
}

if(isset($_GET['heroId']))
{
	$heroId = $_GET['heroId'];
}
else
{
	$heroId = 0;
}

// Get Team- Name, Image and description out of the database
$selectDcheroesteamnames = "SELECT * FROM team" ;
$result = mysqli_query($connection, $selectDcheroesteamnames);

$heroes = [];
$teams = [];

if( mysqli_num_rows($result) > 0);
{
	while($row = mysqli_fetch_assoc($result))
	{
		$teams[] = $row;
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8" />
		<meta name = "description" content = "DC Heroes">
		<link rel = "stylesheet" type = "text/css" href = "css/style.css">
		<title> DC Heroes </title>
		<!--  Favv Icon -->
		<link rel = "schortcut icon" type = "image/jpg" href="img/dcheroeslogo.jpg">
	</head>
	<body>

		<header id = "header">
				<h2  id = "Text"> Heroes  </h2> <a href = "index.php">
				<img id = "Logo" src = "img/dcheroeslogo.jpg" > 
			</a>
		</header>
		<!-- Everything on the page -->
		<div id = "main-container">
		<!-- Everything in the Main Left -->
			<nav id = "main-left">
				<ul>
					<h1> Teams: </h1>
					<?php
					foreach($teams as $key => $team)
					{
					?>
						<li> 
						<?php // Echo's Team id with href so it displays the team and it displays the Name/Image/Members
						echo '<a href="index.php?teamId=' . $team['teamId'] . '">' . '<img src = "img/' . $team['teamImage'] . '" class = "TeamImage">' . '<div class = "TeamName">' . $team['teamName'] .'<br />' . $team['teamDescription'] .  '</li> <br /> </a>';
					}
					?>
				</ul>
				</nav>
				
		<!-- Everything in the Main Center -->
			<div id = "main-center">
				<?php
				$selectDcHeroes = "SELECT * FROM hero WHERE teamId = '$teamId'";
				$result2 = mysqli_query($connection, $selectDcHeroes);

				while($row2 = mysqli_fetch_assoc($result2))
				{
					$heroes[] = $row2;
				}

				foreach($heroes as $key => $hero)
				{	// Making The Class Hero-column where my Hero name, Image, More info button and a small description will be displayed
					echo '<div class = "hero-column">';
					echo '<img src = "img/' . $hero['heroImage']. '"' . 'class = "hero-image">' . '</br>';
					echo '<h2> <div class = "hero-name">' . $hero['heroName'] . '</br> </h2>';
					echo '<div class = "hero-discription">' . $hero['heroDescription'] . '</div> </br>';
					echo '<a class = "more-info" href = "index.php?teamId='. $hero['teamId'] . '&heroId=' . $hero['heroId'] . '">' .  'More Info' . '</a> </div>';
				}
				?>
				
			</div>
		<!-- Everything in the Main Right -->
			<div id = "main-right">
				<?php

				if(isset($_GET['heroId']))
				{
					//SpecificHeroInformation
					$selectDcHeroes = "SELECT * FROM hero WHERE heroId = '$heroId'";
					$result2 = mysqli_query($connection, $selectDcHeroes);

					//Comment
					$selectComment = "SELECT * FROM rating NATURAL JOIN hero WHERE heroId = '$heroId'";
					$resultComment = mysqli_query($connection, $selectComment);
					while($row3 = mysqli_fetch_assoc($resultComment))
					{
						$ratingreview[] = $row3;
					}

					while($row2 = mysqli_fetch_assoc($result2))
					{
						$Heroesdescription[] = $row2;
					}

					foreach($Heroesdescription as $key => $herodescription)
					{ // Echo's Name, Description, Image and a Form with a comment system
						echo '<div class = "right-column">';
						echo '<img src = "img/' . $herodescription['heroImage']. '"' . 'class = "right-hero-image">' . '<br />';
						echo '<h2> <div class = "right-hero-name">' . $herodescription['heroName'] . '<br /> </h2>';
						echo '<div class = "right-hero-description">' . '<div class = "info"> Info: </div> <br /> <br />' .  $herodescription['heroDescription'] . '<br /> <br />' . '</div>';
						echo '<div class = "right-hero-power">' . '<div class = "info"> Powers: </div> <ul>' .  $herodescription['heroPower']  . '</ul>  </div> <hr />'; ?>

						<div class = "Form"> <br />
							<form action = "index.php?teamId=<?php echo $herodescription['teamId']; ?>&heroId=<?php echo $herodescription['heroId']; ?>" method = "POST">  
							<input type = "hidden" name = "heroId" value = "<?php echo $herodescription['heroId'];?> " />
									
								<ul>
								Review: <br />
								<li>
									<textarea rows ="5" cols = "60" name = "textArea" required></textarea>
								</li>
								<li> <!-- Submit Button -->
									<input type = "Submit" value = "Submit" name = "register">
								</li>
								</ul>
							</form>
						</div>
						<hr />
					<?php
					// Checks if $Ratingreview exists
					if(isset($ratingreview))
					{
						foreach($ratingreview as $key => $ratingReviews)
						{	// Echo's the Comment Date and the Comment itself
							echo '<div class = "commentSection">';
							echo $ratingReviews['ratingDate'] . '<br />' . '<br />';
							echo $ratingReviews['ratingReview'] . '<br />' . '</div>' ;
						}	
					}
					}
				}
				?>
			</div>
		</div>

	</body>
</html>