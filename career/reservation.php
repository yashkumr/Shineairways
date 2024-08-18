<?php
ob_start();
session_start();

include 'connection.php';

$nameErr = $emailErr = $contactErr = null;
$name = $email = $contact = $course = null;
$flag = true;

if (isset($_POST['submit'])) {
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $contact = mysqli_real_escape_string($con, $_POST['contact']);
  $course = mysqli_real_escape_string($con, $_POST['course']);
  $created_at = date('Y-m-d h:i:s');

  //number validation
  if (!preg_match('/^[0-9]{10,10}+$/', $contact)) {
    $contactErr = "Invalid Phone Number";
    $flag = false;
  }
  //number validation end

  // email validation
  $emailQuery = "Select * from course_reservation where email = '$email'";
  $emailQueryCheck = mysqli_query($con, $emailQuery);

  if (mysqli_num_rows($emailQueryCheck) > 0) {
    $emailErr =  "This Email is already register";
    $flag = false;
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

      $emailErr = "$email is not a valid";
      $flag = false;
    }
  }
  //email validation end
  if ($flag && isset($_POST['check'])) {
    $reservationData = "INSERT INTO course_reservation(name, contact, email, course, created_at) VALUES ('$name','$contact','$email','$course','$created_at')";
    $result = mysqli_query($con, $reservationData);
    if ($result) {

      //email send code
      $to = 'hr@shineairways.com';
      $subject = "Seat Reservation Enquiry Notification";
      $message = "
            <html>
            <head>
              <title>'$subject'</title>
            </head>
            <body>
              <h1 style='color: orange; text-align: center;'> ShineAirways</h1>
              <hr>
              <p style='text-align: center; font-size: 20px; font-weight: bold;'>Applied For: $course</p>
              <hr>
              <p>Name: $name</p>
              <hr>
              <p>Email: $email</p>
              <hr>
              <p>Contact: $contact</p>
              <hr>
            </body>
            </html>
            ";
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= 'From: <' . $email . '>' . "\r\n";
      $mail = mail($to, $subject, $message, $headers);


      $to1 = $email;
      $subject1 = "ShineAirways Course Registration";
      $message1 = "Thanks You For Regsitering you details on Shine Airways We will reach out to you soon";
      $headers1 = "MIME-Version: 1.0" . "\r\n";
      $headers1 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers1 .= 'From: <info@shineairways.com>' . "\r\n";
      $mail1 = mail($to1, $subject1, $message1, $headers1);

      //email code end

      echo "<script>alert('Thank you for registration with us.Our team will contact you soon')</script>";

    }
  }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <title>Document</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <!-- Option 1: Include in HTML -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <!--  -->
  <link rel="stylesheet" href="../styles.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css" rel="stylesheet" />
  <!-- <link rel="stylesheet" href="css.css" /> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />



</head>


<section class="top-bar">
  <section class="black-bar container-fluid" style="background-color: #182128; height:40px; color: white;">
    <div class="row d-flex align-item-center p-2">
      <i class="col-md-2 offset-1  bi bi-envelope-fill" style="font-size: 15px;"> info@shineairways.com</i>
      <i class="col bi bi-geo-alt" style="font-size: 14px;">G-69, Sector-63, Noida Gautam Buddh Nagar Uttar Pradesh – 201301</i>
      <i class="col-md-2  bi bi-telephone-plus-fill"> +91-88606 91383</i>
    </div>
  </section>
</section>
<!-- navbar -->
<section id="navbar-mains" style="position:sticky; top:0; z-index:9999; ">
  <nav class="navbar navbar-expand-lg navbar-light bg-light p-sticky" style="position: relative; z-index: 999;">
    <div class="container-fluid">
      <a class="navbar-brand nav-logo" href="#"><img src="https://shineairways.com/wp-content/uploads/2022/05/Shine-Airways-Final-Plane-Logo.png" style="width: 120px; height: auto;"> </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ps-auto me-auto mx-3 mb-2 mb-lg-0 ms-sm-0 sm-hr   top-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/">HOME</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../about.html">ABOUT US</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../ourservices.html">SERVICES</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../career.html">CAREER</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">BLOG</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../contact-us.html">CONTACT US</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link active" aria-current="page" href="../academy.html">
              ACADEMY</a>
          </li>
        </ul>
        <!-- <div class="d-flex login" style="flex-direction: column;">
                <div class="d-flex" style="display: block;">
                    <i class="fa fa-phone-square" aria-hidden="true" style="color: #1f0acb;"></i> -->
        <button class="m-sm-0" style=" background-color: rgb(66, 100, 248);  color:rgb(240, 236, 236); border: 0px;   border-radius: 5px;; padding: 11px; font-size: 14px;">Login
          or Create Account</button>
      </div>

    </div>
    </div>
    </div>
  </nav>
</section>
<!-- navbar -->

<div class="container m-auto my-4 mb-5">



  <form class="border shadow " action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
    <h4 class=" text-white text-center py-3 bg-primary" >SEAT RESERVATION</h4>

    <div class="py-5 px-3">
      <div class="mb-3">
        <input type="text" placeholder="Full Name" class="form-control fs-5" name="name" id="name" required>
        <span style="color:red"><?php if (isset($nameErr)) echo $nameErr ?></span>
      </div>
      <div class="mb-3">
        <input type="email" placeholder="Enter your Email" class="form-control fs-5" name="email" value="<?php echo $email ?>" id="email" required>
        <span style="color:red"><?php if (isset($emailErr)) echo $emailErr ?></span>
      </div>
      <div class="mb-3">
        <input type="text" placeholder="Mobile No" class="form-control fs-5" name="contact" value="<?php echo $contact?>" id="number" maxlength="10" required>
        <span style="color:red"><?php if (isset($contactErr)) echo $contactErr ?></span>
      </div>
      <div class="mb-3">
        <label for="course" class="form-label fs-5">Select course <span class="text-danger">*</span></label>
        <select name="course" id="course" class="form-select fs-5" required>
          <option value="" selected disabled>Select Course</option>
          <option value="Project Management">Project Management</option>
          <option value="Tourism Management">Tourism Management</option>
          <option value="Airport Grooud staff">Airport Grooud staff</option>
          <option value="HR Management">HR Management</option>
          <option value="Customer Relationship Manager">Customer Relationship Manager</option>
          <option value="PSA">PSA</option>
          <option value="CSA">CSA</option>
          <option value="Travel & Tourism">Travel & Tourism</option>
          <option value="Sale And Retail Management">Sale And Retail Management</option>
          <option value="Business Development Management">Business Development Management</option>
          <option value="Ticket Support Executive">Ticket Support Executive</option>
          <option value="Cabin Crew">Cabin Crew</option>
        </select>
      </div>
      <div class="my-3 form-check">
        <input type="checkbox" class="form-check-input" name="check" id="check">
        <label class="form-check-label fs-5" for="check">I have read and agree the <a href="../terms&condition.html" class="text-primary text-decoration-none"><b>term & conditions* </b></a></label>
      </div>
      <button type="submit" name="submit" class="btn btn-lg btn-primary">Submit</button>
    </div>
  </form>


</div>



<!-- footer -->
<footer id="footer">
  <div class="footer-top" style="background-color: #28333c; color:#fff;">
    <div class="container">
      <div class="row">

        <div class="col-lg-3 col-md-6 footer-contact">
          <h3 style="color:white;">Shine Airways</h3>
          <p style="color:white;">
            G-69, Sector-63, Noida Gautam <br> Buddh Nagar Uttar Pradesh – 201301
            <br><br>
            <strong>Phone:</strong><br> +91-88606 91383<br>
            <strong>Email:</strong><br> info@shineairways.com<br>
          </p>
        </div>

        <div class="col-lg-3 col-md-6 footer-links">
          <h4 style="color:white">Useful Links</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a style="color:white" ; href="../index.html">Home</a></li>
            <li><i class="bx bx-chevron-right"></i> <a style="color:white" ; href="../about.html">About us</a></li>
            <li><i class="bx bx-chevron-right"></i> <a style="color:white" ; href="../product.html">Product</a></li>
            <li><i class="bx bx-chevron-right"></i> <a style="color:white" ; href="../contact.html">Contact Us</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6 footer-links" style="color:white;">
          <h4 style="color:white;">Our Products</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a style="color: white;" href="../privacypolicy.html">Privacy Policy</a></li>
            <li><i class="bx bx-chevron-right"></i> <a style="color: white;" href="../terms&condition.html">Terms & Conditions</a></li>
            <li><i class="bx bx-chevron-right"></i> <a style="color: white;" href="../about.html">About Us</a></li>
            <li><i class="bx bx-chevron-right"></i> <a style="color: white;" href="../ourservices.html">Services</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6 footer-links">
          <h4 style="color:white">Useful Links</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Banglore To Mumbai Flights</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Pune To Delhi Flights</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Delhi To Banglore Flights</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Delhi To Dubai Flights</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Delhi To Singapore Flights</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Mumbai To Bangkok Flights</a></li>
          </ul>
        </div>



      </div>
    </div>
  </div>

  <div class="container-fluid" style="background-color: #182128; color:#fff;">

    <div class="container d-md-flex py-4">

      <div class="me-md-auto text-center text-md-start">
        <div class="copyright">
          &copy; Copyright <strong><span style="color:orange">Shine Airways</span> 2019</strong>. All Rights Reserved
        </div>
        <div class="credits">
          Managed by
          <a href="http://shinewebtech.in/" style="text-decoration: none; ">

            ShineWeb
          </a>
        </div>
      </div>
      <div class="social-links text-center text-md-right pt-3 pt-md-0">
        <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
        <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
        <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
        <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
        <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
      </div>
    </div>
  </div>
</footer>
<!-- footer -->

</body>

</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>