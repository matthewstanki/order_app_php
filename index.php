<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "order_app");

if (isset($_POST["add_to_cart"])) {
	if (isset($_SESSION["shopping_cart"])) {
		$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
		if (!in_array($_GET["id"], $item_array_id)) {
			$count = count($_SESSION["shopping_cart"]);
			$item_array = array(
				'item_id'			=>	$_GET["id"],
				'item_name'			=>	$_POST["hidden_name"],
				'item_time'	   	    =>	$_POST["time"],
				'item_status'		=>	$_POST["status"]
			);
			$_SESSION["shopping_cart"][$count] = $item_array;
			file_put_contents('orders.txt', implode(' | ', $item_array) . "\r\n", FILE_APPEND | LOCK_EX);
		}
	} else {
		$item_array = array(
			'item_id'			=>	$_GET["id"],
			'item_name'			=>	$_POST["hidden_name"],
			'item_time'		    =>	$_POST["time"],
			'item_status'		=>	$_POST["status"]
		);
		$_SESSION["shopping_cart"][0] = $item_array;
	}
}
if (isset($_GET["action"])) {
	if ($_GET["action"] == "delete") {
		foreach ($_SESSION["shopping_cart"] as $keys => $values) {
			if ($values["item_id"] == $_GET["id"]) {
				unset($_SESSION["shopping_cart"][$keys]);
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Order_app_php</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link href="/styles/all.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col">
				<h2>Kliknij i zamów</h2>
				<?php
				$query = "SELECT * FROM products ORDER BY id ASC";
				$result = mysqli_query($connect, $query);
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_array($result)) {
				?>
						<div class="btn-group mr-2">
							<form method="post" action="index.php?action=add&id=<?php echo $row["id"]; ?>">
								<div style="padding:5px;" align="center">
									<input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />
									<input type="hidden" name="time" value="<?php echo date('Y-m-d H:i:s'); ?>" />
									<input type="hidden" name="status" value="Oczekuje" />
									<input type="submit" name="add_to_cart" class="btn btn-primary" value="<?php echo $row["name"]; ?>" />
								</div>
							</form>
						</div>
				<?php
					}
				}
				?>
			</div>
			<div class="col">
				<h2>Lista zamówień</h2>
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr>
							<th>Nazwa</th>
							<th>Czas</th>
							<th>Status</th>
							<th>Akcja</th>
						</tr>
						<?php
						if (!empty($_SESSION["shopping_cart"])) {
							$total = 0;
							foreach ($_SESSION["shopping_cart"] as $keys => $values) {
						?>
								<tr>
									<td><?php echo $values["item_name"]; ?></td>
									<td><?php echo $values["item_time"]; ?></td>
									<td><?php echo $values["item_status"]; ?></td>
									<td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>" class="btn btn-secondary btn-square-sm">X</a></td>
								</tr>
							<?php
							}
							?>
						<?php
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
	</div>
	<br />
	<footer class="footer">
		<div class="container">
			<span>
				<a target="_blank" href="https://matthewstanki.github.io/">
					<p class="text-right">Copyright (c) 2022, Mateusz Stankiewicz</p>
				</a>
			</span>
		</div>
	</footer>
	<!-- JavaScripts -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>