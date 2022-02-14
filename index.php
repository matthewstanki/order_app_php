<?php 
session_start();
$connect = mysqli_connect("localhost", "root", "", "order_app");

if(isset($_POST["add_to_cart"]))
{
	if(isset($_SESSION["shopping_cart"]))
	{
		$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
		if(!in_array($_GET["id"], $item_array_id))
		{
			$count = count($_SESSION["shopping_cart"]);
			$item_array = array(
				'item_id'			=>	$_GET["id"],
				'item_name'			=>	$_POST["hidden_name"],
				'item_time'	   	    =>	$_POST["time"],
                'item_status'		=>	$_POST["status"]
			);
			$_SESSION["shopping_cart"][$count] = $item_array;
		}
	}
	else
	{
		$item_array = array(
			'item_id'			=>	$_GET["id"],
			'item_name'			=>	$_POST["hidden_name"],
			'item_time'		    =>	$_POST["time"],
			'item_status'		=>	$_POST["status"]
		);
		$_SESSION["shopping_cart"][0] = $item_array;
	}
}
if(isset($_GET["action"]))
{
	if($_GET["action"] == "delete")
	{
		foreach($_SESSION["shopping_cart"] as $keys => $values)
		{
			if($values["item_id"] == $_GET["id"])
			{
				unset($_SESSION["shopping_cart"][$keys]);
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="main-container" style="height: 100%; display: flex;">
		<div class="left-container" style="flex: 1 1 0; margin: 20px;">
        <h2>Kliknij i zamów</h2>
			<?php
                $query = "SELECT * FROM products ORDER BY id ASC";
				$result = mysqli_query($connect, $query);
				if(mysqli_num_rows($result) > 0)
				{
					while($row = mysqli_fetch_array($result))
					{
				?>
			<div class="col-md-5">
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
        <div class="right-container" style="flex: 1 1 0; margin: 20px;">
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
					if(!empty($_SESSION["shopping_cart"]))
					{
						$total = 0;
						foreach($_SESSION["shopping_cart"] as $keys => $values)
						{
					?>
					<tr>
						<td><?php echo $values["item_name"]; ?></td>
						<td><?php echo $values["item_time"]; ?></td>
						<td><?php echo $values["item_status"]; ?></td>
						<td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><button type="button" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-remove"></span></button></a></td>
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
	<br />
	</body>
</html>

