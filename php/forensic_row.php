<?php
    // Start the session
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: ../index.php");
        exit();
    }

    include "connection.php";

    $id = $_GET['specimen_id'];

    $detail_check = $conn->prepare("SELECT * FROM specimen WHERE specimen_id = $id");
    $detail_check->execute();
    $detail_result = $detail_check->get_result();

    while ($user_row = $detail_result->fetch_assoc()) {
        $collectionNumber=$user_row['specimen_collectionNumber'];
        $sex=$user_row['specimen_sex'];
        $age=$user_row['specimen_age'];
        $weight=$user_row['specimen_weight'];
        $isVouchered=$user_row['specimen_isVouchered'];
        $storageLocationVoucheredSpecimen=$user_row['specimen_storageLocationVoucheredSpecimen'];
        $locationCapture=$user_row['specimen_locationCapture'];
        $latitude=$user_row['specimen_latitude'];
        $longitude=$user_row['specimen_longitude'];
        $class=$user_row['specimen_class'];
        $genus=$user_row['specimen_genus'];
        $species=$user_row['specimen_species'];
        $sampleMethod=$user_row['specimen_sampleMethod'];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="../css/forensic_row.css">
    </head>
    <body>
        <div class="grid-container center">
            <div class="item1">
                <?php 
                    include "sidenav.php";
                ?>
            </div>
            <div class="item2">
                <h1>Sample ID: SFC-GRB-<?php echo($id) ?></h1>
            </div>
            <div class="item3">
                <div id="info-container" class="center">
                    <p><b>Sampling collection number:</b><span><?php echo($collectionNumber) ?></span></p>
                    <p><b>Location of capture:</b><span><?php echo($locationCapture) ?></p>
                    <p><b>GPS of capture:</b><span><?php echo($latitude . "&nbsp&nbsp" . $longitude) ?></span></p>
                    <p><b>Class:</b><span><?php echo($class) ?></span></p>
                    <p><b>Genus:</b><span><?php echo($genus) ?></span></p>
                    <p><b>Species:</b><span><?php echo($species)?></span></p>
                    <p><b>Sex:</b><span><?php echo($sex) ?></span></p>
                    <p><b>Age:</b><span><?php echo($age) ?></span></p>
                    <p><b>Weight:</b><span><?php echo($weight) ?></span></p>
                    <p><b>Vouchered?:</b><span><?php echo($isVouchered) ?></span></p>
                    <p><b>Storage location of vouchered?:</b><span><?php echo($storageLocationVoucheredSpecimen) ?></span></p>
                    <p><b>Sampling Method:</b><span><?php echo($sampleMethod) ?></span></p>
                </div>

                <a href="addsubsample.php?specimen_id=<?php echo $id ?>" id="add-subsample-button">
                    Add Subsample
                </a>

                <form id="search-form">
                    <input type="text" id="search-box">
                    <input type="submit" id="search-button" value="Q">
                </form>

                <table class="center">
                    <thead>
                        <tr>
                            <th>Subsample ID</th>
                            <th>Raw Sequence</th>
                            <th>Cleaned Sequence</th>
                            <th>Photo Identification</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = $conn->prepare("SELECT subSample_id, subSample_rawSequence, subSample_cleanedSequence, subSample_photoIdentification FROM subSample WHERE specimen_id = $id");
                            $sql->execute();
                            $result = $sql->get_result();

                            $rawSequence_status="../assets/image/wrong.png";
                            $cleanedSequence_status="../assets/image/wrong.png";
                            $photoIdentification_status="../assets/image/wrong.png";

                            while ($subsample_row = $result->fetch_assoc()){
                                if($subsample_row['subSample_rawSequence']!=""){
                                    $rawSequence_status="../assets/image/correct.png";
                                }else{
                                    $rawSequence_status="../assets/image/wrong.png";
                                }
                                
                                if($subsample_row['subSample_cleanedSequence']!=""){
                                    $cleanedSequence_status="../assets/image/correct.png";
                                }else{
                                    $cleanedSequence_status="../assets/image/wrong.png";
                                }
    
                                if($subsample_row['subSample_photoIdentification']!=""){
                                    $photoIdentification_status="../assets/image/correct.png";
                                }else{
                                    $photoIdentification_status="../assets/image/wrong.png";
                                }
                                
                                echo "<tr onclick='window.location.href = \"subSample_edit.php?subSample_id=" . $subsample_row['subSample_id'] . "\";'>";
                                echo "<td>" . $subsample_row['subSample_id'] . "</td>";
                                echo "<td><img src='" . $rawSequence_status . "'></td>";
                                echo "<td><img src='" . $cleanedSequence_status . "'></td>";
                                echo "<td><img src='" . $photoIdentification_status . "'></td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>