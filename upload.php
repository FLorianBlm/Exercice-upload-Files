<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="imageUpload">Upload an profile image</label>    
        <input type="file" name="file[]" id="imageUpload" multiple/>
        <button>Send</button>
    </form>

    <?php
    if (isset($_FILES['file']['name'][0])) {
        $files = $_FILES['file'];
        $allowed = array('png' , 'jpg', 'gif');

        
        foreach($files['name'] as $position => $file_name) {
            $file_tmp = $files['tmp_name'][$position];
            $file_size = $files['size'][$position];

            $file_ext = explode('.', $file_name);
            $file_ext = strtolower(end($file_ext));

            if(in_array($file_ext, $allowed)) {

                if($file_size <= 1000000) {
                    $file_name_new = uniqid('', true) . '.' . $file_ext;
                    $file_destination = 'uploads/' . $file_name_new;

                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        $uploaded[$position] = $file_destination;
                    } else {
                        $failed[$position] = "[{$file_name}] n'a pas été téléchargé.";
                    }
                } else {
                    $failed[$position] = "[{$file_name}] est trop lourd.";
                }
            } else {
                $failed[$position] = "[{$file_name}], le format '{$file_ext}' n'est pas supporté.";
            }
        }
        if (!empty($uploaded)) {
            echo "Des fichiers ont été chargés" . '<br>';
        }
        if (!empty($failed)) {
            echo 'Une erreur est survenu pour ' . $failed[0];
        }

        $displays = new FilesystemIterator("./uploads/", FilesystemIterator::KEY_AS_FILENAME);
        foreach ($displays as $display) {
            echo '<img src=' . $display . ' width=100px heigth=100px >' . '\n';
            echo  $display->getFilename() . '\n';
        }
    }
    ?>
</body>
</html>
