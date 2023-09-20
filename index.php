<?php
require_once "conf/config.php";

$errors = array();
$successMessage = "";

// Fungsi untuk mendapatkan daftar tugas
function getTasks($conn)
{
    $sql = "SELECT id, title, status FROM tasks";
    $result = $conn->query($sql);

    $tasks = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    }

    return $tasks;
}

// Fungsi untuk menambahkan tugas baru
function addTask($conn, $title, $description)
{
    $status = "Pending";
    $sql = "INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $description, $status);
    $stmt->execute();
    $stmt->close();
}

// Fungsi untuk mengubah status tugas
function updateTaskStatus($conn, $taskId, $newStatus)
{
    $sql = "UPDATE tasks SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $taskId);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_status"])) {
        $taskId = $_POST["task_id"];
        $newStatus = $_POST["new_status"];
        updateTaskStatus($conn, $taskId, $newStatus);
    }
}

// Validasi input tambah tugas baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_task"])) {
    $title = $_POST["title"];
    $description = $_POST["description"];

    // Validasi judul tugas baru (tidak boleh kosong)
    if (empty($title)) {
        $errors[] = "Judul tugas tidak boleh kosong.";
    }

    // Validasi input judul
    if (!empty($title) && !preg_match("/^[a-zA-Z0-9\s]+$/", $title)) {
        $errors[] = "Judul hanya boleh berisi huruf, angka, dan spasi.";
    }

    // Jika tidak ada error, tambahkan tugas baru
    if (empty($errors)) {
        try {
            addTask($conn, $title, $description);
            $successMessage = "Tugas baru berhasil ditambahkan.";
        } catch (Exception $e) {
            $errors[] = "Terjadi kesalahan dalam menambahkan tugas: " . $e->getMessage();
        }
    }
}

// Validasi input pencarian
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $searchTitle = $_POST["search_title"];
    $searchStatus = $_POST["search_status"];

    // Validasi input judul
    if (!empty($searchTitle) && !preg_match("/^[a-zA-Z0-9\s]+$/", $searchTitle)) {
        $errors[] = "Judul hanya boleh berisi huruf, angka, dan spasi.";
    }

    // Validasi input status
    $validStatusOptions = array("Pending", "In Progress", "Completed");
    if (!empty($searchStatus) && !in_array($searchStatus, $validStatusOptions)) {
        $errors[] = "Status tidak valid.";
    }

    //Jika $searchTitle kosong, maka $searchStatus harus dipilih    
    if (empty($searchTitle) && empty($searchStatus)) {
        $errors[] = "Harap isi judul atau pilih status.";
    }

    $tasks = getTasks($conn);
    if (empty($errors)) {
        // Proses pencarian hanya jika tidak ada error
        $sql = "SELECT id, title, status FROM tasks WHERE 1=1";

        if (!empty($searchTitle)) {
            $sql .= " AND title LIKE '%$searchTitle%'";
        }

        if (!empty($searchStatus)) {
            $sql .= " AND status = '$searchStatus'";
        }

        $result = $conn->query($sql);

        $tasks = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
        }
    }
} else {
    // Jika bukan permintaan pencarian, ambil semua tugas
    $tasks = getTasks($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Task Manager</title>
    <style>
        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }
    </style>
</head>

<body>
    <!-- Form untuk pencarian -->
    <h2>Cari Tugas</h2>
    <form method="POST">
        <label for="search_title">Judul:</label>
        <input type="text" id="search_title" name="search_title">

        <label for="search_status">Status:</label>
        <select id="search_status" name="search_status">
            <option value="">-- Semua --</option>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
        </select>

        <input type="submit" name="search" value="Cari">
    </form>

    <!-- Tampilkan pesan error jika ada -->
    <?php
    if (!empty($errors)) {
        echo '<div class="error-message">';
        foreach ($errors as $error) {
            echo '<p>' . $error . '</p>';
        }
        echo '</div>';
    }
    ?>

    <h1>Task Manager</h1>

    <!-- Form untuk menambah tugas baru -->
    <h2>Tambah Tugas Baru</h2>
    <form method="POST">
        <label for="title">Judul Tugas:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="description">Deskripsi Tugas:</label>
        <textarea id="description" name="description"></textarea><br>

        <input type="submit" name="add_task" value="Tambah Tugas">
    </form>
    <?php
    // Tampilkan pesan sukses jika ada
    if (!empty($successMessage)) {
        echo '<div class="success-message">' . $successMessage . '</div>';
    }
    ?>

    <!-- Daftar tugas -->
    <h2>Daftar Tugas</h2>
    <table border="1">
        <tr>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($tasks as $task) { ?>
            <tr>
                <td><?php echo $task["title"]; ?></td>
                <td><?php echo $task["status"]; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $task["id"]; ?>">
                        <select name="new_status">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                        <input type="submit" name="update_status" value="Ubah Status">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>