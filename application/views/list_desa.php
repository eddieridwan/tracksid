<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="<?php echo base_url()?>assets/js/bootstrap.min.js"></script>
</head>
<body>

<div id="container">

<form id="mainform" name="mainform" action="" method="post">

  <select name="filter" onchange="$('#'+'mainform').attr('action','<?php echo site_url("laporan/filter")?>');$('#'+'mainform').submit();">
    <option value="">Semua</option>
    <option value="1" <?php if($filter==1) :?>selected<?php endif?>>Online</option>
    <option value="2" <?php if($filter==2) :?>selected<?php endif?>>Offline</option>
  </select>

  <table>
    <tr>
      <th>No.</th>
      <th>Desa</th>
      <th>Kecamatan</th>
      <th>Kabupaten</th>
      <th>Provinsi</th>
      <th>Web</th>
      <th>Versi</th>
      <th>Tgl Rekam</th>
    </tr>
    <?php foreach ($list_desa as $urut => $desa) : ?>
      <tr>
        <td><?php echo $offset+$urut+1;?></td>
        <td><?php echo $desa['nama_desa']; ?></td>
        <td><?php echo $desa['nama_kecamatan']; ?></td>
        <td><?php echo $desa['nama_kabupaten']; ?></td>
        <td><?php echo $desa['nama_provinsi']; ?></td>
        <td><?php echo $desa['url_referrer']; ?><br><?php echo $desa['client_ip']?></td>
        <td><?php echo $desa['opensid_version']; ?></td>
        <td><?php echo $desa['tgl']; ?></td>
      </tr>
    <?php endforeach;?>
  </table>
</form>

<?php echo $links ?>

</div>

</body>
</html>
