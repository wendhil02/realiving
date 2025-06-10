<?php
include "../database.php";
include "../header/header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - RealLiving Design Center</title>
    <link rel="icon" type="image/png" href="../images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="./appointment.css">
</head>
<body>
    <section class="sub-header">
        <h1>Contact Us</h1>
    </section>
<section class="card">
  <h2>Schedule your visit</h2>
  <form>
    <div class="form-grid">
      <!-- Row 1: Name + Nickname -->
      <div class="form-group">
        <label>NAME</label>
        <input type="text">
      </div>
      <div class="form-group optional">
        <label>WHAT SHOULD WE CALL YOU? <span>(Optional)</span></label>
        <input type="text">
      </div>

      <!-- Row 2: Email + Phone + Date (3-column) -->
      <div class="form-group triple-grid">
        <label>EMAIL</label>
        <input type="email">
      </div>
      <div class="form-group triple-grid">
        <label>CONTACT NUMBER</label>
        <div class="phone-input">
          <span>+63</span>
          <input type="tel">
        </div>
      </div>
      <div class="form-group triple-grid">
        <label>PREFERRED DATE</label>
        <input type="date">
      </div>

      <!-- Full-width Textarea -->
      <div class="form-notes">
        <label>ADDITIONAL NOTES</label>
        <textarea></textarea>
      </div>
    </div>

    <!-- Submit Button -->
    <div class="form-submit">
      <button type="submit" class="submit-btn">SCHEDULE AN APPOINTMENT</button>
    </div>
  </form>
</section>

  <section class="card">
    <div class="calendar-title">Available this week</div>
    <div class="calendar">
      <div class="calendar-day-name">Sun</div>
      <div class="calendar-day-name">Mon</div>
      <div class="calendar-day-name">Tue</div>
      <div class="calendar-day-name">Wed</div>
      <div class="calendar-day-name">Thu</div>
      <div class="calendar-day-name">Fri</div>
      <div class="calendar-day-name">Sat</div>

      <!-- Week Dates Example -->
      <div></div><div></div><div class="highlight">2<br><small>Presentation</small></div><div></div>
      <div class="today">5</div><div></div><div></div>
      <div></div><div class="highlight">9<br><small>Measurement</small></div><div></div><div></div><div></div><div></div><div></div>
      <div></div><div class="highlight">16<br><small>Explore</small></div><div></div><div></div><div></div><div></div><div></div>
      <div class="highlight">23<br><small>Presentation</small></div><div></div><div></div><div></div><div></div><div></div><div></div>
    </div>
  </section>

</body>
</html>
