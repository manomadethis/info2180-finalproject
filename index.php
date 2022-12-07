<?php
	if(isset($_SESSION['logined_user'])){
        header("Location:http://localhost/info2180-finalproject/dashboard.php" );     
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <link rel="stylesheet" href = "styles.css">
        <script type="text/javascript" src="login.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Dolphin CRM</title>

		<link href="style.css" type="text/css" rel="stylesheet" />
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
      crossorigin="anonymous">
      
		<script src="scripts/nav.js" type="text/javascript"></script>
	</head>

	<body>
		<div class="grid-container">
			<header>
				<ul>
					<li id="header">
						<i class="fas fa-bug"></i>
						Dolphin CRM
					</li>
				</ul>
			</header>
			
				<nav>
					<ul>
						<li id="home" class="">
							<i class=""></i>
							Home
						</li>
						<?php if ($_SESSION['session_type'] == 'admin') : ?>
						<li id="new contact" class="">
							<i class=""></i>
							New Contact
						</li>
						<?php endif; ?>
						<li id="users" class="n">
							<i class=""></i>
							Users
						</li>
						<li id="logout" class="nav-div">
							<i class=""></i>
							Logout
						</li>
					</ul>
				</nav>
				<main id="display"></main>
			
		</div>
	</body>
</html>
