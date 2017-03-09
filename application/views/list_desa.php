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
      <th>Akses Terakhir</th>
    </tr>
    <?php foreach ($list_desa as $urut => $desa) : ?>
      <tr>
        <td><?php echo $urut+1;?></td>
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

<div class="uibutton-group">
<?php  if($paging->start_link): ?>
<a href="<?php echo site_url("web/widget/$paging->start_link/$o")?>" class="uibutton">Awal</a>
<?php  endif; ?>
<?php  if($paging->prev): ?>
<a href="<?php echo site_url("web/widget/$paging->prev/$o")?>" class="uibutton">Prev</a>
<?php  endif; ?>
</div>
<div class="uibutton-group">

<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?php echo site_url("web/widget/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
<?php  endfor; ?>
</div>
<div class="uibutton-group">
<?php  if($paging->next): ?>
<a href="<?php echo site_url("web/widget/$paging->next/$o")?>" class="uibutton">Next</a>
<?php  endif; ?>
<?php  if($paging->end_link): ?>
<a href="<?php echo site_url("web/widget/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
<?php  endif; ?>
</div>

</div>

</body>
</html>
