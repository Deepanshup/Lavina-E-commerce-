<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="sasdWwoAAYoZi0sVHrtjsAvG5TIzH6ArKcrta2Vo">

    <title>22Ggroup</title>

    <link rel="icon" sizes="72x72" href="../public/images/favicon-96x96.png">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900%7COpen+Sans:300,400,600,700,800"
          rel="stylesheet">
    <link rel="stylesheet" href="../public/css/font-awesome.min.css">
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/magnific-popup.css">
    <link rel="stylesheet" href="../public/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../public/css/settings.css">
    <link rel="stylesheet" type="text/css" href="../public/css/layers.css">
    <link rel="stylesheet" type="text/css" href="../public/css/navigation.css">
    <link rel="stylesheet" href="../public/css/animate.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link data-style="color-style" rel="stylesheet" href="../public/css/color-blue.css">
    <script src="../public/js/modernizr-2.8.3.min.js"></script>

</head>
<body  >
<div style="text-align: center"><img src="../public/logo.jpeg" style="width:20%"></div>
<div id="app">
    <main class="py-4">
    <div class="main-content section-padding">
        <div class="container">
            <div class="col-md-4 col-md-offset-4">
                    <div class="row">
                        <h3>Drivers list</h3>
					    <table>
					        <col width="250">
					        <col width="250">
					        <col width="250">
					        <col width="250">
						    <thead>
						        <tr>
							        <th>Driver's Name</th>
							        <th>Truck Number</th>
							        <th>Manager's Number</th>
							        <th>Modify</th>
						        </tr>
						    </thead>
						    <tbody>
						    <?php
						        session_start();
						        $servername = "localhost";
                                $username = "nadaa93i_gaurav";
                                $password = "root@123";
                                $dbname = "nadaa93i_truck_app";
                                $end=$_SESSION["end"];;
                                if($end!="true"){
                                    echo '<script>
                                            window.location="../admin/";
                                    </script>';
                                }
                                function accept($pl) {
                                    echo '<script>window.location="../add_driver/update_driver.php?id=';
                                    echo $pl;
                                    echo '"</script>';
                                    
                                }
                                function reject($un) {
                                    $serverna = "localhost";
                                    $userna = "nadaa93i_gaurav";
                                    $passwo = "root@123";
                                    $dbne = "nadaa93i_truck_app";
                                    try {
                                        $co = new mysqli($serverna, $userna, $passwo,$dbne);
                                        $sq = "DELETE FROM driver WHERE id='$un'";
                                        $co->query($sq);
                                        $_SESSION["end"]="true";
                                    }
                                    catch(PDOException $e)
                                    {
                                        echo $sql . "<br>" . $e->getMessage();
                                    }
                                }
                                try {
                                    $conn = new mysqli($servername, $username, $password,$dbname);
                                    $results = mysqli_query($conn,"SELECT * FROM `driver`");
                                    while($row=mysqli_fetch_row($results)){
                                        $one=urldecode($row[0]);
                                        $two=urldecode($row[1]);
                                        $three=urldecode($row[2]);
                                        $four=urldecode($row[3]);
                                        $s=$four;
                                    echo "
                                        <tr>
                                            <td>$one</td>
                                            <td>$two</td>
                                            <td>$three</td>
                                            <td>
                                                <form action='' method='post'>
                                                    <input type='hidden' name='sub' value='1'>
                                                    <button id='accept' name ='accept' value = '$s' style='margin:5px;' >Edit</button>
								                    <button id='reject' name ='reject' value = '$s' style='margin:5px;'>Delete</button>
								                <form>
								            </td>
								        </tr>
								    ";
								    if(isset($_POST["accept"]))
								    {              
								        accept($_POST["accept"]);
								        
								    }
								    if(isset($_POST["reject"]))
								    {
								        reject($_POST["reject"]);
								    }
                                    }
                                }
                                catch(PDOException $e)
                                {
                                    echo $sql . "<br>" . $e->getMessage();
                                }
                                ?>
						    </tbody>
					   </table>
                    </div>
            </div>
        </div>
    </div>
    </main>
    
</div>


    <script src="../public/js/jquery-2.2.4.min.js"></script>
    <script src="../public/js/bootstrap.min.js"></script>
    <script src="../public/js/mixitup.min.js"></script>
    <script src="../public/js/select2.min.js"></script>
    <script src="../public/js/jquery.colorbox-min.js"></script>
    <script src="../public/js/jquery.waypoints.min.js"></script>
    <script src="../public/js/jquery.counterup.min.js"></script>
    <script src="../public/js/slick.min.js"></script>
    <script src="../public/js/jquery.plugin.js"></script>
    <script src="../public/js/jquery.countdown.min.js"></script>
    <script src="../public/js/wow.min.js"></script>
    <script src="../public/js/revolution/jquery.themepunch.tools.min.js"></script>
    <script src="../public/js/revolution/jquery.themepunch.revolution.min.js"></script>
    <script src="../public/js/revolution/extensions/revolution.extension.actions.min.js"></script>
    <script src="../public/js/revolution/extensions/revolution.extension.layeranimation.min.js"></script>
    <script src="../public/js/revolution/extensions/revolution.extension.navigation.min.js"></script>
    <script src="../public/js/revolution/extensions/revolution.extension.slideanims.min.js"></script>
    <script src="../public/js/revolution-active.js"></script>
    <script src="../public/js/custom.js"></script>
</body>
</html>
