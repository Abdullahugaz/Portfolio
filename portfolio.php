<?php
include 'config/db.php';

$query = "SELECT * FROM profile LIMIT 2";
$result = $conn->query($query);
$profile = $result->fetch_assoc();
$photo = 'uploads/' . basename($profile['photo_path']);

$cv = 'uploads/' . basename($profile['cv_path']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($profile['name']) ?> - Portfolio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode: 'class' };
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
  <style>
    body { transition: background 0.5s, color 0.5s; }
    .animate-fade { animation: fadeIn 1.2s ease-in-out both; }
    @keyframes fadeIn {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .glow-border {
      border: 3px solid #00ffff;
      box-shadow: 0 0 20px #00ffff;
      transition: transform 0.3s;
    }
    .glow-border:hover { transform: scale(1.05); 
    }
    .glow-border {
  border: 3px solid white;
  box-shadow: 0 0 20px white;
}

  </style>
</head>
<body class="dark bg-gray-900 text-white font-sans">

  <!-- Navbar -->
  <nav class="flex justify-between items-center px-10 py-6">
    <h1 class="text-xl font-bold">Portfolio.</h1>
    <ul class="flex space-x-6 text-gray-300 font-medium font-poppins">
      <li><a href="#" class="text-cyan-400">Home</a></li>
      <li><a href="#">About</a></li>
      <li><a href="#">Skills</a></li>
      <li><a href="#">Portfolio</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
  </nav>

  <!-- Hero Section -->
  <section class="flex flex-col md:flex-row items-center justify-between px-10 py-20 animate-fade">
    <!-- Left -->
    <div class="md:w-1/2">
      <p class="text-xl">Hello, It's Me</p>
      <h1 class="text-4xl font-bold mt-2"><?= htmlspecialchars($profile['name']) ?></h1>
      <h2 class="text-2xl text-cyan-400 font-semibold mt-1">And I'm a <?= htmlspecialchars($profile['role']) ?></h2>
      <p class="mt-4 text-gray-400"><?= htmlspecialchars($profile['description']) ?></p>

      <div class="flex space-x-4 mt-6">
        <a href="<?= htmlspecialchars($profile['facebook_link']) ?>" class="bg-white p-3 rounded-full hover:bg-cyan-400 transition" title="Facebook">
          <i class="fab fa-facebook-f text-blue-600"></i>
        </a>
        <!-- <a href="<?= htmlspecialchars($profile['linkedin_link']) ?>" class="bg-white p-3 rounded-full hover:bg-cyan-400 transition" title="LinkedIn">
          <i class="fab fa-linkedin-in text-blue-500"></i>
        </a> -->
        <a href="<?= htmlspecialchars($profile['github_link']) ?>" class="bg-white p-3 rounded-full hover:bg-cyan-400 transition" title="GitHub">
          <i class="fab fa-github text-black"></i>
        </a>
      </div>

      <a href="<?= htmlspecialchars($cv_path) ?>" download class="inline-block mt-6 px-6 py-2 text-black font-semibold rounded-full bg-white hover:bg-cyan-400 transition glow-border">
  Download CV
</a>


    </div>

    <!-- Right -->
    <div class="md:w-1/2 mt-10 md:mt-0 flex justify-center">
  <!-- Photo -->
<div class="md:w-1/2 mt-10 md:mt-0 flex justify-center">
  <div class="rounded-full p-1 glow-border">
    <?php if (!empty($photo) && file_exists($photo)): ?>
      <img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($profile['name']) ?>" class="rounded-full w-60 h-60 object-cover" />
    <?php else: ?>
      <p class="text-red-400">No photo uploaded.</p>
    <?php endif; ?>
  </div>
</div>
</div>

    </div>
  </section>

</body>
</html>
