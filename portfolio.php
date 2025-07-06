<?php
include 'config/db.php';

// Profile
$profile = $conn->query("SELECT * FROM profile LIMIT 1")->fetch_assoc();
$photo = 'uploads/' . basename($profile['photo_path']);
$cv = 'uploads/' . basename($profile['cv_path']);

// About
$about = $conn->query("SELECT content FROM about LIMIT 1")->fetch_assoc()['content'] ?? '';

// Skills
$skills = [];
$skills_result = $conn->query("SELECT * FROM skills");
while ($row = $skills_result->fetch_assoc()) $skills[] = $row;

// Contact Info
$contact = $conn->query("SELECT * FROM contact LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($profile['name']) ?> - Portfolio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { darkMode: 'class' };</script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
  <style>
    .section { display: none; opacity: 0; transform: translateY(20px); transition: all 0.6s ease; }
    .section.active { display: block; opacity: 1; transform: translateY(0); }
    html { scroll-behavior: smooth; }
  </style>
</head>
<body class="bg-white text-black dark:bg-gray-900 dark:text-white font-sans" data-aos-easing="ease" data-aos-duration="1000" data-aos-delay="0">


<!-- Navigation -->
<div class="flex justify-end items-center gap-6 px-6 py-4 text-base font-medium">
<div class="text-left">
  <h1 class="text-2xl font-extrabold text-white tracking-wide animate-glow">
    Portfolio
  </h1>
</div>
  <a href="#" onclick="showSection('home')">Home</a>
  <a href="#" onclick="showSection('about')">About</a>
  <a href="#" onclick="showSection('skills')">Skills</a>
  <a href="#" onclick="showSection('portfolio')">Portfolio</a>
  <a href="#" onclick="showSection('contact')">Contact</a>
  <button id="toggleTheme" class="text-2xl focus:outline-none"><span id="themeIcon">🌙</span></button>
</div>

<!-- HERO SECTION -->
<section id="home" class="section active px-10 py-20 animate-fade" data-aos="fade-up">
  <div class="flex flex-col md:flex-row items-center justify-between">
    <div class="md:w-1/2" data-aos="fade-right">
      <p class="text-xl">Hello, It's Me</p>
      <h1 class="text-4xl font-bold mt-2"><?= htmlspecialchars($profile['name']) ?></h1>
      <h2 class="text-2xl text-cyan-600 dark:text-cyan-400 font-semibold mt-1">And I'm a <?= htmlspecialchars($profile['role']) ?></h2>
      <p class="mt-4"><?= htmlspecialchars($profile['description']) ?></p>
      <div class="mt-6 space-x-4">
        <a href="<?= htmlspecialchars($profile['facebook_link']) ?>" target="_blank"><i class="fab fa-facebook text-2xl"></i></a>
        <a href="<?= htmlspecialchars($profile['github_link']) ?>" target="_blank"><i class="fab fa-github text-2xl"></i></a>
      </div>
      <a href="<?= htmlspecialchars($cv) ?>" download class="mt-4 inline-block px-4 py-2 bg-white text-black rounded glow-border">Download CV</a>
    </div>
    <div class="md:w-1/2 flex justify-center mt-10 md:mt-0" data-aos="fade-left">
      <img src="<?= $photo ?>" alt="<?= $profile['name'] ?>" class="rounded-full w-60 h-60 object-cover border-4 border-white transition-transform duration-500 hover:scale-105" />
    </div>
  </div>
</section>

<!-- ABOUT -->
<section id="about" class="section px-10 py-12" data-aos="fade-up">
  <h2 class="text-2xl font-bold text-cyan-500 mb-4">About Me</h2>
  <p><?= htmlspecialchars($about) ?></p>
</section>

<!-- SKILLS -->
<section id="skills" class="section px-10 py-12" data-aos="fade-up">
  <h2 class="text-2xl font-bold text-cyan-500 mb-4">Skills</h2>
  <ul class="list-disc list-inside space-y-2">
    <?php foreach ($skills as $skill): ?>
      <li><?= htmlspecialchars($skill['skill_name']) ?> - <span class="italic text-sm"><?= htmlspecialchars($skill['skill_level']) ?></span></li>
    <?php endforeach; ?>
  </ul>
</section>

<!-- PORTFOLIO -->
<section id="portfolio" class="section px-10 py-12" data-aos="fade-up">
  <h2 class="text-2xl font-bold text-cyan-500 mb-4">Portfolio / Projects</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow hover:scale-105 transition-transform" data-aos="zoom-in">
      <h3 class="text-lg font-semibold">Project 1</h3>
      <p class="text-sm text-gray-600 dark:text-gray-300">Description of the project</p>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow hover:scale-105 transition-transform" data-aos="zoom-in">
      <h3 class="text-lg font-semibold">Project 2</h3>
      <p class="text-sm text-gray-600 dark:text-gray-300">Description of the project</p>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="section px-10 py-12" data-aos="fade-up">
  <h2 class="text-2xl font-bold text-cyan-500 mb-4">Contact Me</h2>
  <form id="contactForm" class="space-y-4">
    <input type="text" name="name" placeholder="Your Name" required class="w-full p-2 rounded bg-gray-100 dark:bg-gray-800" />
    <input type="email" name="email" placeholder="Your Email" required class="w-full p-2 rounded bg-gray-100 dark:bg-gray-800" />
    <textarea name="message" placeholder="Your Message" required class="w-full p-2 rounded bg-gray-100 dark:bg-gray-800"></textarea>
    <button type="submit" class="px-4 py-2 bg-cyan-500 text-white rounded hover:bg-cyan-600 transition-all duration-300">Send Message</button>
    <p id="formResponse" class="text-sm mt-2"></p>
  </form>
  <div class="mt-6">
    <p><strong>Email:</strong> <?= htmlspecialchars($contact['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($contact['phone']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($contact['address']) ?></p>
  </div>
</section>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();

  function showSection(id) {
    document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
    const target = document.getElementById(id);
    target.classList.add('active');
    AOS.refresh();
  }

  const htmlEl = document.documentElement;
  const toggleBtn = document.getElementById('toggleTheme');
  const icon = document.getElementById('themeIcon');
  if (localStorage.getItem('theme') === 'light') {
    htmlEl.classList.remove('dark');
    icon.textContent = '🌞';
  }
  toggleBtn.addEventListener('click', () => {
    htmlEl.classList.toggle('dark');
    const isDark = htmlEl.classList.contains('dark');
    icon.textContent = isDark ? '🌙' : '🌞';
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  });

  document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const response = await fetch('send_email.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.text();
    document.getElementById('formResponse').textContent = result;
    this.reset();
  });
</script>
</body>
</html>
