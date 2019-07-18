<?php
require "connection.php";

?>
<!DOCTYPE html>
<html>
	<head>
	    <title>Cek Resi JNE, J&T, Pos, Tiki, Sicepat, Wahana JNT dan Semua Kurir Indonesia - Trentech.id</title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Cek resi pengiriman JNE, tracking J&T, lacak kiriman Pos 2018. Paket 46 ekspedisi: Wahana, Sicepat, TIKI online, Pandu logistic, Dakota, Lion Parcel, dll">
        <meta name="keywords" content="jne tracking, lacak paket, cek resi pos, resi tiki online, cek resi j&t, kirim barang, cek paket, paket kiriman, sicepat, standard express, Acommerce" />
		<link rel="image_src" href="https://i0.wp.com/res.cloudinary.com/trentechid/image/upload/v1494215498/fav_2_cdtbwi.png?fit=200%2C200&ssl=1" />
        <link rel="shortcut icon" href="https://i0.wp.com/res.cloudinary.com/trentechid/image/upload/v1494215498/fav_2_cdtbwi.png?fit=200%2C200&ssl=1">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
		<style>
			h2{
				margin-top:20px;
			}
			#view-ongkir{
				max-height:500px;
				overflow-x:hidden;
				overflow-y:auto;
				border:1px;
			}
			body { 
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
    background: #f9f9f9;
}

.header {
  overflow: hidden;
  background-color: #fff;
  padding: 5px 5px;
  box-shadow: 0px 2px 5px #f1f1f1;
}

.header a {
  float: left;
  color: black;
  text-align: center;
  padding: 2px;
  text-decoration: none;
  font-size: 18px; 
  line-height: 25px;
  border-radius: 4px;
}

.header a.logo {
  font-size: 25px;
  font-weight: bold;
}



.header a.active {
  color: #606060;
}

.header-right {
  float: right;
  padding: 12px;
}

@media screen and (max-width: 500px) {
  .header a {
    float: none;
    display: block;
    text-align: center;
  }
  .header-right {
    float: none;
    display: none;
  }
}
		</style>
	</head>
	<body>
	    
	    <div class="header">
  <a href="https://www.trentech.id" class="logo"><img src="https://www.trentech.id/wp-content/uploads/2018/04/logo-trentech-dekstop.png"></a>
  <div class="header-right">
    <!--<a class="active" href="/">Home</a>-->
    <a href="https://www.trentech.id/cek-ongkir">Cek Ongkir</a>
    <!--<a href="#about">About</a>-->
  </div>
</div>
	    
		<div class="container-fluid">
			<h2 align="center">Cek Resi</h2>

			<div class="row">
				
				<div class="col-md-12">
					<form method="post" action="">
						<div class="mb-3">
							<label for="courier">Kurir</label>
							<select class="custom-select d-block w-100" id="courier" name="courier" required>
								<option value="">Pilih Kurir</option>
								
<?php
$sql_kurir = "SELECT * FROM kht_kurir WHERE cek_resi = 'YES' ORDER BY id";
$r_kurir = $conn->query($sql_kurir);
if ($r_kurir->num_rows > 0) {
	while($row_kurir = $r_kurir->fetch_assoc()) {
?>
								<option value="<?php echo $row_kurir['kode_kurir']; ?>"><?php echo $row_kurir['nama_kurir']; ?></option>
<?php
	}
}
?>
							</select>
						</div>
						
						<div class="mb-3">
							<label for="noresi">No Resi</label>
							<input type="text" id="noresi" name="noresi" class="form-control" placeholder="Masukkan No Resi" required />
						</div>
						
						<hr class="mb-4">
						<button class="btn btn-primary btn-lg btn-block" type="button" onClick="cekresi();">PROCESS</button>
					</form>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<hr class="mb-3">
					<div id="view-resi"></div>
					<div align="center">
						<!--<a href="#" target="_blank" title="Cek Resi">Cek Resi</a>-->
					</div>
				</div>
			</div>
		</div>
	
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script>
		function cekresi() {
			var courier = $("#courier").val();
			var noresi = $("#noresi").val();
			
			if (courier.length == 0) {
				alert("Silahkan pilih kurir");
			} else if (noresi.length == 0) {
				alert("Anda belum memasukkan no resi pengiriman");
			} else {
				$("#view-resi").html('<div align="center"><img src="progress.gif" /></div>');
				$.ajax({
					url:"get_lacak.php",
					data:"noresi="+noresi+"&kurir="+courier,
					cache:false,
					success:function(msg) {
						$("#view-resi").html(msg);
					}
				});
			}
		}
		</script>
	</body>
</html>