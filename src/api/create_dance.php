<?php
require __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form fields
    $dance_name  = $_POST['dance_name']    ?? '';
    $category_id = $_POST['category_id']   ?? '';
    $region      = $_POST['region']        ?? '';
    $description = $_POST['description']   ?? '';

    // Retrieve coordinate values for the pin (as integers)
    $pin_x = isset($_POST['pin_x']) ? (int)$_POST['pin_x'] : null;
    $pin_y = isset($_POST['pin_y']) ? (int)$_POST['pin_y'] : null;

    // Check for required fields, including the coordinates
    if (empty($dance_name) || empty($category_id) || empty($region) || $pin_x === null || $pin_y === null) {
        exit('Please fill in all required fields and place the pin on the map.');
    }

    // Prepare media (file upload) if provided
    $media_id = 'NULL';
    if (isset($_FILES['dance_image']) && $_FILES['dance_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../public/assets/images';
        $filename  = time() . '_' . basename($_FILES['dance_image']['name']);
        $targetFile = $uploadDir . '/' . $filename;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['dance_image']['tmp_name'], $targetFile)) {
            $mediaPath = 'assets/images/' . $filename;
            $altText   = $dance_name . ' image';

            $stmtMedia = $conn->prepare("INSERT INTO media (media_url, alttext) VALUES (?, ?)");
            $stmtMedia->bind_param("ss", $mediaPath, $altText);
            $stmtMedia->execute();
            $media_id = $stmtMedia->insert_id;
            $stmtMedia->close();
        } else {
            exit('Error uploading file. Please try again.');
        }
    }

    $approved = 2;

    $sqlDance = "INSERT INTO dances (dance_name, category_id, description, media_id, region, approved, x, y)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtDance = $conn->prepare($sqlDance);

    // If no media was uploaded, set media_id to null
    if ($media_id === 'NULL') {
        $media_id = null;
    }

    // Bind parameters: "s" (string) for dance_name, "i" (integer) for category_id, "s" for description,
    // "i" for media_id, "i" for region, "i" for approved, and "i" for both pin_x and pin_y.
    $stmtDance->bind_param("sisiiiii", $dance_name, $category_id, $description, $media_id, $region, $approved, $pin_x, $pin_y);

    if ($stmtDance->execute()) {
        echo "Dance created successfully and is waiting for approval!";
    } else {
        echo "Error creating dance: " . $conn->error;
    }

    $stmtDance->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
