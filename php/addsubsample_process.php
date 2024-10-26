<?php
    // Start the session
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['userid'])) {
        header("Location: ../index.php");
        exit();
    }

    include "connection.php";

    $id = $_GET['id'];

    // Check if user_id is set in session
    if (isset($_SESSION['userid'])) {
        $dateCollected = $_POST['date-collected'];
        $storageLocation = $_POST['storage-location'];
        $sampleType = $_POST['sample-type'];
        $dnaLabName = $_POST['dna-lab-name'];
        $dnaLabNumber = $_POST['dna-lab-number'];
        $dnaExtractionSize = $_POST['dna-extraction-size'];
        $pcrLabName = $_POST['pcr-lab-name'];
        $pcrLabNumber = $_POST['pcr-lab-number'];
        $primerUsed = $_POST['primer-used'];
        $blastResult = $_POST['blast-result'];
        $cleaningLabName = $_POST['cleaning-lab-name'];
        $cleaningLabNumber = $_POST['cleaning-lab-number'];
        $rawSequenceTemp = $_FILES['raw-sequence']['tmp_name'];
        $cleanedSequenceTemp = $_FILES['cleaned-sequence']['tmp_name'];
        $photoSequenceTemp = $_FILES['photo-identification']['tmp_name'];
        date_default_timezone_set('Asia/Kuching');
        $image_createdAt = date("dmYHis");

        $tube_label_sql = $conn->prepare("SELECT sampleType_tube_label FROM sampleType WHERE sampleType_id = ?");
        $tube_label_sql->bind_param("i", $sampleType); // Assuming sampleType_id is an integer
        $tube_label_sql->execute();

        $result = $tube_label_sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tube_label = $row['sampleType_tube_label'];
        } else {
            echo "No tube label found for the given sample type.";
        }

        $tube_label_sql->close();
        
        if ($rawSequenceTemp != ""){
            $raw_file_extension = strtolower(pathinfo($_FILES['raw-sequence']['name'], PATHINFO_EXTENSION));
            $raw_newfilename = "raw_sequence_" . $id . "_" . $tube_label . "." . $raw_file_extension;
            $raw_target_dir = "../assets/uploads/raw_sequence/";
            $raw_target_file = $raw_target_dir . $raw_newfilename;
            
            if(move_uploaded_file($rawSequenceTemp, $raw_target_file)) {
                echo "Nice";
            } else {
                echo "Error uploading file.";
            }
        }else{
            $raw_target_file = "";
        }
        
        if ($cleanedSequenceTemp != ""){
            $cleaned_file_extension = strtolower(pathinfo($_FILES['cleaned-sequence']['name'], PATHINFO_EXTENSION));
            $cleaned_newfilename = "cleaned_sequence_" . $id . "_" . $tube_label . "." . $cleaned_file_extension;
            $cleaned_target_dir = "../assets/uploads/cleaned_sequence/";
            $cleaned_target_file = $cleaned_target_dir . $cleaned_newfilename;

            if(move_uploaded_file($cleanedSequenceTemp, $cleaned_target_file)) {
                echo "Nice";
            } else {
                echo "Error uploading file.";
            }
        }else{
            $cleaned_target_file = "";
        }
        
        if ($photoSequenceTemp != ""){
            $photo_file_extension = strtolower(pathinfo($_FILES['photo-identification']['name'], PATHINFO_EXTENSION));
            $photo_newfilename = "photo_identification_" . $id . "_" . $tube_label . "." . $photo_file_extension;
            $photo_target_dir = "../assets/uploads/photo_identification/";
            $photo_target_file = $photo_target_dir . $photo_newfilename;

            if(move_uploaded_file($photoSequenceTemp, $photo_target_file)) {
                echo "Nice";
            } else {
                echo "Error uploading file.";
            }
        }else{
            $photo_target_file = "";
        }

        

        $sql = $conn->prepare("INSERT INTO subSample(
            specimen_id,
            sampleType_id,
            subSample_dateCollected,
            subSample_storageLocation,
            subSample_pcrLabName,
            subSample_pcrLabNumber,
            subSample_primerUsed,
            subSample_blastResult,
            subSample_cleaningLabName,
            subSample_cleaningLabNumber,
            subSample_rawSequence,
            subSample_cleanedSequence,
            subSample_photoIdentification) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");

            echo $id . "<br>";
            echo $sampleType . "<br>";
            echo $dateCollected . "<br>";
            echo $storageLocation . "<br>";
            echo $pcrLabName . "<br>";
            echo $pcrLabNumber . "<br>";
            echo $primerUsed . "<br>";
            echo $blastResult . "<br>";
            echo $cleaningLabName . "<br>";
            echo $cleaningLabNumber . "<br>";
            echo $raw_target_file . "<br>";
            echo $cleaned_target_file . "<br>";
            echo $photo_target_file . "<br>";

        $conn->close();
    }
?>