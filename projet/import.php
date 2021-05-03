<?php
require("../config/database.php");
include '../vendor/autoload.php';



if(isset($_FILES['import_excel']))
{
 $file_name = $_FILES['import_excel']['name'];
 $allowed_extension = array('xls', 'csv', 'xlsx');
 $file_array = explode(".",$file_name);
 $file_extension = end($file_array);

 if(in_array($file_extension, $allowed_extension))
 {
  $file_name = time() . '.' . $file_extension;
  move_uploaded_file($_FILES['import_excel']['tmp_name'], $file_name);
  $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

  $spreadsheet = $reader->load($file_name);

  unlink($file_name);

  $data = $spreadsheet->getActiveSheet()->toArray();

  foreach($data as $row)
  {
   $insert_data = array(
    ':nom_projet'  => $row[0],
    ':identifiant_projet'  => $row[1],
    ':montant_ht_sans_frais_EG'  => $row[2],
    ':montant_ht_frais_EG'  => $row[3],
    ':maitre_ouvrage'  => $row[4],
    ':maitre_ouvrage_delegue'  => $row[5],
    ':architecte'  => $row[6],
    ':delais_travaux'  => $row[7],
    ':image_projet'  => $row[8],
    ':date_projet'  => $row[9],
    ':IGH'  => $row[10],
    ':nombre_niveau_infra'  => $row[11],
    ':nombre_niveau_super'  => $row[12],
    ':localisation_geographique'  => $row[13],
    ':lien_excel'  => $row[14],
    ':type_projet'  => $row[15],
    ':indice_confiance'  => $row[16],
    'indice_projt'  => $row[17]

   );

   $query = "
    INSERT INTO projet
    (nom_projet , identifiant_projet , montant_ht_sans_frais_EG , montant_ht_frais_EG , maitre_ouvrage , maitre_ouvrage_delegue , 
    architecte , delais_travaux , image_projet , date_projet , IGH , nombre_niveau_infra , nombre_niveau_super ,
    localisation_geographique , lien_excel , type_projet , indice_confiance , indice_projt  ) 

    VALUES  (:nom_projet , :identifiant_projet , :montant_ht_sans_frais_EG , :montant_ht_frais_EG , :maitre_ouvrage , :maitre_ouvrage_delegue , 
    :architecte , :delais_travaux , :image_projet , :date_projet , :IGH , :nombre_niveau_infra , :nombre_niveau_super ,
    :localisation_geographique , :lien_excel , :type_projet , :indice_confiance , :indice_projt ) 
    ";

   $statement = $pdo->prepare($query);
   $statement->execute($insert_data);
  }
  $message = '<div class="alert alert-success">Data Imported Successfully</div>';
   echo '<p> projet importé avec succès </p>';
   echo '<p><a href="importprojet.php">retour </a></p>';
 }
 else
 {
    echo '<p> <div class="alert alert-danger">Only .xls .csv or .xlsx file allowed</div></p>';
 }
} 
else
{
    echo '<p> <div class="alert alert-danger">Please Select File</div></p>';
}
?>
