<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<title>OpenSID Dashboard</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
  <script
  src="https://code.jquery.com/jquery-2.2.4.js"
  integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
  crossorigin="anonymous"></script>
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
<body class="w3-light-grey">





<!-- !PAGE CONTENT! -->
<div class="w3-main" style="padding-left:210px;position:relative;">
  <!-- Sidebar/menu -->
  <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:210px;position:absolute!important;top:0;left:0;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s8 w3-bar">
      <span><strong>Welcome, You are now accessing the Dashboard</strong></span><br>
    </div>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <a href="http://localhost/tracksid/index.php/dashboard" class="fa fa-share-alt w3-bar-item w3-button w3-padding w3-blue">Dashboard</a>
    <a href="http://localhost/tracksid-master" class=" fa fa-users w3-bar-item w3-button w3-padding w3-blue">Database</a>
    
</div>
  </nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h1><b>Opensid's Dashboard</b></h1>
  </header>

  <h2>Welcome to Desa OpenSID</h2>
        <div>
            <input type="hidden" name="arg_id_local" value="<?php echo $is_local?>">
            <input type="hidden" name="arg_kab" value="<?php echo $kab?>">
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" >Filter</h3>
            </div>
            <div class="panel-body">
                <form id="form-filter" class="form-horizontal">
                    <div class="table-group">
                        <label for="is_local" class="col-sm-2 control-label">Jenis Server</label>
                        <div class="col-sm-2">
                            <?php echo $form_server; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kab" class="col-sm-2 control-label">Kabupaten</label>
                        <div class="col-sm-2">
                            <?php echo $form_kab; ?>
                        </div>
                    </div>
                    <div class="table-group">
                        <label for="akses" class="col-sm-2 control-label">Akses Terakhir</label>
                        <div class="col-sm-2">
                            <?php echo $form_akses; ?>
                        </div>
                    </div>
                                        <div class="form-group">
                        <label for="akses" class="col-sm-2 control-label">Villages</label>
                        <div class="col-sm-2">
                            <?php echo $form_akses; ?>
                        </div>
                    </div>
                                        <div class="table-group">
                        <label for="akses" class="col-sm-2 control-label">Current Status</label>
                        <div class="col-sm-2">
                            <?php echo $form_akses; ?>
                        </div>
                    </div>
                                        <div class="form-group">
                        <label for="akses" class="col-sm-2 control-label">Regencies</label>
                        <div class="col-sm-2">
                            <?php echo $form_akses; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="LastName" class="col-sm-2 control-label"></label>
                        <div class="col-sm-4">
                            <button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
                            <button type="button" id="btn-reset" class="btn btn-default">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<!--Google Map-->

<div id="googleMap" style="width:100%;height:400px;"></div>

<script>
  var map;

function myMap() {
  var mapProp= {
      center:new google.maps.LatLng(0.7893, 113.9213),
      zoom:5,
  };
map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
}


// Then just loop through the data and add markers to the map
function initMarkers(data) {
  var marker;
		for(var i=0;i < data.length; i++) {
			var position = {lat: parseFloat(data[i][10]), lng: parseFloat(data[i][11])};
			marker = new google.maps.Marker({
				position: position,
				map: map
			});
		}
		map.panTo(marker.position);
	}

// When user clicks on filter button make a server request to load the data
$(document).ready(function() {
  $('#btn-filter').on('click', function() {

  $.post("<?php echo site_url('laporan/ajax_list')?>", {
    is_local: $('#is_local').val(),
    kab: $('#kab').val(),
    akses: $('#akses').val()
  }, function (data) {
    // We get the data from server and parse it to json
    var data = JSON.parse(data);

    // Then add markers from the data
    initMarkers(data.data);
    
  })

  
  })
  
})

</script>
</body>
</html>

<!--Google API KEY-->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlh3unALdz5EJCFYHcr_xF9uVkrRF4rZA&callback=myMap">
</script>
<br>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.dropbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #f9f9f9;
    min-width: 180px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #3e8e41;
}
</style>
</head>
<body>

  <!--Status Blocks-->

<!--Online Users-->

  <div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter">
      <div class="w3-container w3-red w3-padding-16">
        <div class="w3-left"><i class="fa fa-user w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>52</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Villages Added Recently</h4>
      </div>
    </div>

    <!--Number of Offline Users-->
    <div class="w3-quarter">
      <div class="w3-container w3-blue w3-padding-16">
        <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>99</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Regencies Added Recently</h4>
      </div>
    </div>

    <!--Past Week-->
    <div class="w3-quarter">
      <div class="w3-container w3-teal w3-padding-16">
        <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>24</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Provinces without Installation</h4>
      </div>
    </div>

    <!--Past Month-->
    <div class="w3-quarter">
      <div class="w3-container w3-orange w3-text-white w3-padding-16">
        <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>50</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>No. Installations with data not matching registered Village</h4>
      </div>
    </div>
  </div>


<!--Google Chart-->

  <script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer",
    {
      title:{
      text: "User Activity"
      },

      axisX: {
        valueFormatString: "MMM",
        interval: 1,
        intervalType: "month"
      },

      data: [
      {
        type: "stackedBar",
        legendText: "Teluk Waienga (North)",
        showInLegend: "true",
        dataPoints: [
        { x: new Date(2012, 01, 1), y: 71 },
        { x: new Date(2012, 02, 1), y: 55},
        { x: new Date(2012, 03, 1), y: 50 },
        { x: new Date(2012, 04, 1), y: 65 },
        { x: new Date(2012, 05, 1), y: 95 }

        ]
      },
        {
        type: "stackedBar",
        legendText: "IIIekimok Village (South)",
        showInLegend: "true",
        dataPoints: [
        { x: new Date(2012, 01, 1), y: 71 },
        { x: new Date(2012, 02, 1), y: 55},
        { x: new Date(2012, 03, 1), y: 50 },
        { x: new Date(2012, 04, 1), y: 65 },
        { x: new Date(2012, 05, 1), y: 95 }

        ]
      },
        {
        type: "stackedBar",
        legendText: "Desa Lerahiga (East)",
        showInLegend: "true",
        dataPoints: [
        { x: new Date(2012, 01, 1), y: 71 },
        { x: new Date(2012, 02, 1), y: 55},
        { x: new Date(2012, 03, 1), y: 50 },
        { x: new Date(2012, 04, 1), y: 65 },
        { x: new Date(2012, 05, 1), y: 95 }

        ]
      },

        {
        type: "stackedBar",
        legendText: "Merdeka Village (West)",
        showInLegend: "true",
        dataPoints: [
        { x: new Date(2012, 01, 1), y: 61 },
        { x: new Date(2012, 02, 1), y: 75},
        { x: new Date(2012, 03, 1), y: 80 },
        { x: new Date(2012, 04, 1), y: 85 },
        { x: new Date(2012, 05, 1), y: 105 }

        ]
      }
      ]
    });

    chart.render();
  }
  </script>
 <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<body>
  <div id="chartContainer" style="height: 300px; width: 100%;">
  </div>



  <!--Feeds Panel-->

  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
      <div class="w3-twothird">
        <h5>Feeds</h5>
        <table class="w3-table w3-striped w3-white">
          <tr>
            <td><i class="fa fa-user w3-text-blue w3-large"></i></td>
            <td>New user has signed in</td>
            <td><i>10 mins</i></td>
          </tr>
          <tr>
            <td><i class="fa fa-bell w3-text-red w3-large"></i></td>
            <td>A new village has been added.</td>
            <td><i>15 mins</i></td>
          </tr>
          <tr>
            <td><i class="fa fa-users w3-text-yellow w3-large"></i></td>
            <td>A user has shared something new</td>
            <td><i>17 mins</i></td>
          </tr>
          <tr>
            <td><i class="fa fa-comment w3-text-red w3-large"></i></td>
            <td>You have a new notification</td>
            <td><i>25 mins</i></td>
          </tr>
          <tr>
            <td><i class="fa fa-bookmark w3-text-blue w3-large"></i></td>
            <td>Daily Activity has been updated.</td>
            <td><i>28 mins</i></td>
          </tr>
          <tr>
            <td><i class="fa fa-laptop w3-text-red w3-large"></i></td>
            <td>You have a new Message</td>
            <td><i>35 mins</i></td>
          </tr>
          <tr>
            <td><i class="fa fa-share-alt w3-text-green w3-large"></i></td>
            <td>New shares.</td>
            <td><i>39 mins</i></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <hr>

  <!--Google Chart-->

<  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Employed',     11],
          ['Unemployed',      2],
          ['Educational',  2],
          ['Farming ', 2],
          ['Retired',    7]
        ]);

        var options = {
          title: 'Traditional lifestyle',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart_3d" style="width: 800px; height: 500px;"></div>
  </body>

  <!--Statistical Analysis Charts-->
  <div class="w3-container">
    <h5>Statistical Analysis</h5>
    <p>New Users</p>
    <div class="w3-grey">
      <div class="w3-container w3-center w3-padding w3-green" style="width:25%">+25%</div>
    </div>

    <p>Current Users</p>
    <div class="w3-grey">
      <div class="w3-container w3-center w3-padding w3-orange" style="width:50%">50%</div>
    </div>

    <p>Non-Current Users</p>
    <div class="w3-grey">
      <div class="w3-container w3-center w3-padding w3-red" style="width:75%">75%</div>
    </div>
  </div>
  <hr>

  <div class="w3-container">
    <h5>Villages Added in recent week</h5>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <tr>
        <td>Bandung</td>
        <td>45 mins ago</td>
      </tr>
      <tr>
        <td>Bangka parent</td>
        <td>15 hours ago</td>
      </tr>
      <tr>
        <td>Coa</td>
        <td>17 hours ago</td>
      </tr>
      <tr>
        <td>Lingga</td>
        <td>2 days ago</td>
      </tr>
      <tr>
        <td>Madiun</td>
        <td>4 days ago</td>
      </tr>
      <tr>
        <td>Sregen</td>
        <td>6 days ago</td>
      </tr>
    </table><br>
    <button class="w3-button w3-dark-grey">Load More Villages<i class="fa fa-arrow-right"></i></button>
  </div>
<br>
  <div class="w3-container w3-dark-grey w3-padding-32">
    <div class="w3-row">
      <div class="w3-container w3-third">
        <h5 class="w3-bottombar w3-border-green">About</h5>
        <p>About OpenSID</p>
        <p>OpenSID Community</p>
        <p>Developent</p>
      </div>
      <div class="w3-container w3-third">
        <h5 class="w3-bottombar w3-border-red">Join the OpenSID Community</h5>
        <p>New to OpenSID? Sign up here</p>
        <p>Forget password? Click here</p>
        <p>Other Enquires</p>
      </div>
      <div class="w3-container w3-third">
        <h5 class="w3-bottombar w3-border-orange">More Information</h5>
        <p>News</p>
        <p>Events</p>
        <p>Careers</p>
        <p>Contacts</p>
      </div>
    </div>
  </div>


  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-light-grey"></footer>

  <!-- End page content -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
    }
}

// Close the sidebar with the close button
function w3_close() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
}
</script>

  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-light-grey"></footer>

  <!-- End page content -->
</div>
<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
    }
}

// Close the sidebar with the close button
function w3_close() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
}
</script>
</body>
</html>