<?php
session_start();
include '../config/db.php';

// INSERT or UPDATE
if (isset($_POST['save'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $role = $_POST['role'];
  $description = $_POST['description'];
  $facebook = $_POST['facebook_link'];
  $linkedin = $_POST['linkedin_link'];
  $github = $_POST['github_link'];

  $photo_path = '';
  $cv_path = '';

  if ($id) {
    // Get existing file paths
    $stmt_old = $conn->prepare("SELECT photo_path, cv_path FROM profile WHERE id = ?");
    $stmt_old->bind_param("i", $id);
    $stmt_old->execute();
    $stmt_old->bind_result($existing_photo_path, $existing_cv_path);
    $stmt_old->fetch();
    $stmt_old->close();
    $photo_path = $existing_photo_path;
    $cv_path = $existing_cv_path;
  }

  // Handle Photo Upload
  if (!empty($_FILES['photo_file']['name'])) {
    $photo_ext = pathinfo($_FILES['photo_file']['name'], PATHINFO_EXTENSION);
    $photo_new_name = 'photo_' . time() . '.' . $photo_ext;
    $photo_path = 'uploads/' . $photo_new_name;
    move_uploaded_file($_FILES['photo_file']['tmp_name'], '../' . $photo_path);
  }

  // Handle CV Upload
  if (!empty($_FILES['cv_file']['name']) && $_FILES['cv_file']['error'] === 0) {
    $cv_ext = pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION);
    $cv_new_name = 'cv_' . time() . '.' . $cv_ext;
    $cv_path = 'uploads/' . $cv_new_name;
    move_uploaded_file($_FILES['cv_file']['tmp_name'], '../' . $cv_path);
  }

  if ($id) {
    $stmt = $conn->prepare("UPDATE profile SET name=?, role=?, description=?, facebook_link=?, linkedin_link=?, github_link=?, photo_path=?, cv_path=? WHERE id=?");
    $stmt->bind_param("ssssssssi", $name, $role, $description, $facebook, $linkedin, $github, $photo_path, $cv_path, $id);
  } else {
    $stmt = $conn->prepare("INSERT INTO profile (name, role, description, facebook_link, linkedin_link, github_link, photo_path, cv_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $role, $description, $facebook, $linkedin, $github, $photo_path, $cv_path);
  }

  $stmt->execute();
  header("Location: manage_profile.php");
  exit();
}

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM profile WHERE id = $id");
  header("Location: manage_profile.php");
  exit();
}

// FETCH
$search = $_GET['search'] ?? '';
$result = $conn->query("SELECT * FROM profile WHERE name LIKE '%$search%' OR role LIKE '%$search%' ORDER BY id DESC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Profile - Tailwind</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6 text-gray-800">
  <div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Manage Portfolio Profile</h1>

    <!-- Search -->
    <form method="get" class="mb-4 flex gap-2">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name or role" class="flex-1 p-2 border rounded-md shadow-sm">
      <button class="px-4 py-2 bg-blue-600 text-white rounded-md">Search</button>
    </form>

    <!-- Add Button -->
    <button onclick="editProfile()" class="mb-4 px-4 py-2 bg-green-600 text-white rounded-md">+ Add New</button>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-md overflow-hidden">
        <thead class="bg-gray-800 text-white">
          <tr>
            <th class="p-3 text-left">Photo</th>
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Role</th>
            <th class="p-3 text-left">CV</th>
            <th class="p-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="border-t hover:bg-gray-50">
            <td class="p-3">
              <?php if ($row['photo_path']): ?>
                <img src="<?= $row['photo_path'] ?>" class="w-12 h-12 rounded-full object-cover">
              <?php endif; ?>
            </td>
            <td class="p-3"><?= htmlspecialchars($row['name']) ?></td>
            <td class="p-3"><?= htmlspecialchars($row['role']) ?></td>
            <td class="p-3">
              <?php if ($row['cv_path']): ?>
                <a href="<?= htmlspecialchars($row['cv_path']) ?>" download class="text-blue-600 underline">Download CV</a>


              <?php endif; ?>
            </td>
            <td class="p-3 space-x-2">
              <button onclick='editProfile(<?= json_encode($row) ?>)' class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
              <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="px-3 py-1 bg-red-600 text-white rounded">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Background -->
  <div id="modalBg" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <form method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-md w-full max-w-2xl">
      <h2 class="text-xl font-bold mb-4">Profile</h2>
      <input type="hidden" name="id" id="id">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block">Name</label><input type="text" name="name" id="name" class="w-full p-2 border rounded" required></div>
        <div><label class="block">Role</label><input type="text" name="role" id="role" class="w-full p-2 border rounded" required></div>
        <div class="md:col-span-2"><label class="block">Description</label><textarea name="description" id="description" class="w-full p-2 border rounded" rows="3" required></textarea></div>
        <div><label class="block">Facebook</label><input type="text" name="facebook_link" id="facebook_link" class="w-full p-2 border rounded"></div>
        <div><label class="block">LinkedIn</label><input type="text" name="linkedin_link" id="linkedin_link" class="w-full p-2 border rounded"></div>
        <div><label class="block">GitHub</label><input type="text" name="github_link" id="github_link" class="w-full p-2 border rounded"></div>
        <!-- Image preview -->
<div>
  <label class="block font-medium">Upload Photo</label>
  <input type="file" name="photo_file" id="photo_file" accept="image/*" onchange="previewImage(event)" class="w-full border rounded p-2">
  <img id="photoPreview" class="mt-2 w-20 h-20 object-cover rounded" style="display:none;">
</div>

<!-- CV preview -->
<div>
  <label class="block font-medium">Upload CV (PDF)</label>
  <input type="file" name="cv_file" id="cv_file" accept=".pdf" onchange="previewCV(event)" class="w-full border rounded p-2">
  <p id="cvPreview" class="mt-2 text-sm text-gray-600 hidden"></p>
</div>

      </div>
      <div class="mt-6 flex justify-end space-x-2">
        <button type="submit" name="save" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
      </div>
    </form>
  </div>
<script>
  function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photoPreview');
    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    } else {
      preview.style.display = 'none';
    }
  }

  function previewCV(event) {
    const file = event.target.files[0];
    const label = document.getElementById('cvPreview');
    if (file && file.name.endsWith('.pdf')) {
      label.innerText = 'Selected: ' + file.name;
      label.classList.remove('hidden');
    } else {
      label.innerText = '';
      label.classList.add('hidden');
    }
  }

  function editProfile(data = null) {
    document.getElementById('id').value = data?.id || '';
    document.getElementById('name').value = data?.name || '';
    document.getElementById('role').value = data?.role || '';
    document.getElementById('description').value = data?.description || '';
    document.getElementById('facebook_link').value = data?.facebook_link || '';
    document.getElementById('linkedin_link').value = data?.linkedin_link || '';
    document.getElementById('github_link').value = data?.github_link || '';
    document.getElementById('photo_file').value = '';
    document.getElementById('cv_file').value = '';
    document.getElementById('photoPreview').style.display = 'none';
    document.getElementById('cvPreview').classList.add('hidden');

    document.getElementById('modalBg').classList.remove('hidden');
    document.getElementById('modalBg').classList.add('flex');
  }

  function closeModal() {
    document.getElementById('modalBg').classList.add('hidden');
  }
</script>

</body>
</html>
