<?php
require "connection.php";
require "functions.php";

if (isset($_GET['noresi']) && !empty($_GET['noresi'])) {
	if (isset($_GET['kurir']) && !empty($_GET['kurir'])) {
		$noresi = $_GET['noresi'];
		$kurir = $_GET['kurir'];
		$api = 'isi dengan API Raja Ongkir'; //Isi dengan api dari rajaongkir
		
		$notif = 0;
		
		$sql_lacakpaket = "SELECT * FROM kht_lacakpaket WHERE noresi = '".$noresi."' AND kurir = '".$kurir."'";
		$r_lacakpaket = $conn->query($sql_lacakpaket);
		if ($r_lacakpaket->num_rows > 0) {
			$row_lacakpaket = $r_lacakpaket->fetch_assoc();
			if ($row_lacakpaket['exp_lacak'] < date("Y-m-d H:i:s")) {
				$notif = 0;
			} else {
				$notif = 1;
			}
		} else {
			$notif = 1;
		}
		
		if ($notif == 1) {
			
			
			$lacakpaket = getLacak($noresi,$kurir,$api);
			$data = json_decode($lacakpaket, TRUE);
			
			$new_data_resi = '<table class="table table-bordered" width="100%">';
			
			if (isset($data['rajaongkir']['result'])) {
				$status_paket = $data['rajaongkir']['result']['summary']['status'];
				$kode_kurir = $data['rajaongkir']['result']['summary']['courier_code'];
				$nama_kurir = $data['rajaongkir']['result']['summary']['courier_name'];
				$no_resi = $data['rajaongkir']['result']['summary']['waybill_number'];
				$service_kurir = $data['rajaongkir']['result']['summary']['service_code'];
				
				$tgl_kirim = $data['rajaongkir']['result']['details']['waybill_date'];
				$jam_kirim = $data['rajaongkir']['result']['details']['waybill_time'];
				$berat_barang = $data['rajaongkir']['result']['details']['weight'];
				
				// Detail Pengirim
				$nama_pengirim = $data['rajaongkir']['result']['details']['shippper_name'];
				$alamat_ori1 = $data['rajaongkir']['result']['details']['shipper_address1'];
				$alamat_ori2 = $data['rajaongkir']['result']['details']['shipper_address2'];
				$alamat_ori3 = $data['rajaongkir']['result']['details']['shipper_address3'];
				$kota_ori = $data['rajaongkir']['result']['details']['shipper_city'];
				
				// Alamat singkat pengirim
				$asal = $data['rajaongkir']['result']['details']['origin'];
				
				// Detail Penerima
				$nama_penerima = $data['rajaongkir']['result']['details']['receiver_name'];
				$alamat_dest1 = $data['rajaongkir']['result']['details']['receiver_address1'];
				$alamat_dest2 = $data['rajaongkir']['result']['details']['receiver_address2'];
				$alamat_dest3 = $data['rajaongkir']['result']['details']['receiver_address3'];
				$kota_dest = $data['rajaongkir']['result']['details']['receiver_city'];
				
				// Alamat singkat penerima
				$tujuan = $data['rajaongkir']['result']['details']['destination'];
				
				// Status Pengiriman
				$status_pengiriman = $data['rajaongkir']['result']['delivery_status']['status'];
				$penerima_paket = $data['rajaongkir']['result']['delivery_status']['pod_receiver'];
				$tgl_terima = $data['rajaongkir']['result']['delivery_status']['pod_date'];
				$jam_terima = $data['rajaongkir']['result']['delivery_status']['pod_time'];
				
				if (empty($berat_barang)) {
					$weight = "-";
				} else {
					$weight = $berat_barang;
				}
				
				$new_data_resi .= '
					<tr><th colspan="2" class="text-center"><h4>No Resi<br />'.$no_resi.'</h4></th></tr>
					<tr><td width="40%"><strong>Status</strong></td><td width="60"><strong>'.$status_pengiriman.'</strong></td></tr>
					<tr><td>Kurir</td><td>'.$nama_kurir.'</td></tr>
					<tr><td>Layanan</td><td>'.$service_kurir.'</td></tr>
					<tr><td>Tgl Kirim</td><td>'.date("d-m-Y", strtotime($tgl_kirim))." ".date("H:i", strtotime($jam_kirim)).'</td></tr>
					<tr><td>Berat</td><td>'.$weight.' Kg</td></tr>
					<tr><td>Pengirim</td><td>'.$nama_pengirim.'</td></tr>
					<tr><td>Asal</td><td>'.$asal.'</td></tr>
					<tr><td>Penerima</td><td>'.$nama_penerima.'</td></tr>
					<tr><td>Alamat</td><td>'.$alamat_dest1."<br />".$alamat_dest2."<br />".$alamat_dest3."<br />".$kota_dest.'</td></tr>
					<tr><td>Penerima</td><td>'.$penerima_paket.'</td></tr>
					<tr><td>Tgl Terima</td><td>'.date("d-m-Y", strtotime($tgl_terima))." ".date("H:i", strtotime($jam_terima)).'</td></tr>
					<tr><td class="text-center" colspan="2"><h4>Riwayat Pengiriman</h4></td></tr>';
	
				if (isset($data['rajaongkir']['result']['manifest'])) {
					for($i=0; $i < count($data['rajaongkir']['result']['manifest']); $i++) {
						$kode_manifest = $data['rajaongkir']['result']['manifest'][$i]['manifest_code'];
						$desk_manifest = $data['rajaongkir']['result']['manifest'][$i]['manifest_description'];
						$tgl_manifest = $data['rajaongkir']['result']['manifest'][$i]['manifest_date'];
						$jam_manifest = $data['rajaongkir']['result']['manifest'][$i]['manifest_time'];
						$kota_manifest = $data['rajaongkir']['result']['manifest'][$i]['city_name'];
						
						$new_data_resi .= '<tr><td>'.date("d-m-Y", strtotime($tgl_manifest))." ".date("H:i", strtotime($jam_manifest)).'</td><td>'.$desk_manifest." ".$kota_manifest.'</td></tr>';
					}
				} else {
					$new_data_resi .= '<tr><td colspan="2" class="text-center">Belum ada data</td></tr>';
				}
			} else {
				$new_data_resi .= '<tr><td colspan="2" class="text-center">Data pengiriman tidak ditemukan</td></tr>';
			}
			
			$new_data_resi .= '</table>';
			
			$now = date("Y-m-d H:i:s");
			$d = strtotime("+3 Hours");
			$tgl_exp = date("Y-m-d H:i:s", $d);
			
			if ($r_lacakpaket->num_rows > 0) {
				$fields = "tgl_lacak = '".$now."', exp_lacak = '".$tgl_exp."', data_resi = '".$new_data_resi."'";
				$wheres = "noresi = '".$noresi."' AND kurir = '".$kurir."'";
				$action_data = "UPDATE kht_lacakpaket SET ".$fields." WHERE ".$wheres;
			} else {
				$fields = "noresi,kurir,tgl_lacak,exp_lacak,data_resi";
				$values = "'".$noresi."','".$kurir."','".$now."','".$tgl_exp."','".$new_data_resi."'";
				$action_data = "INSERT INTO kht_lacakpaket (".$fields.") VALUES (".$values.")";
			}
			
			$conn->query($action_data);
			
			echo $new_data_resi;
		} else {
			echo $row_lacakpaket['data_resi'];
		}
	}
}
