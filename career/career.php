<?php
ob_start();
session_start();

include 'connection.php';

$targetDir = "documents/";
$flag = true;

$contactErr =  $emailErr =  $photoErr =  $pincodeErr =  $aadharErr = $panErr = $aadhaarErr = $aviationErr = $photoProperPath = $aviationProperPath = null;
$name = $contact = $fathername = $email = $contact = $dob = $gender = $education = $applied = $address = $state = $pincode = $aadhaarcard = $aviation = NULL;
$file_path = "";

$sucess = '';

if (isset($_POST['submit'])) {

  $name = mysqli_real_escape_string($con, $_POST['name']);
  $fathername = mysqli_real_escape_string($con, $_POST['fathername']);
  $contact = mysqli_real_escape_string($con, $_POST['contact']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $dob = mysqli_real_escape_string($con, $_POST['dob']);
  $gender = mysqli_real_escape_string($con, $_POST['gender']);
  $education = mysqli_real_escape_string($con, $_POST['education']);
  $applied = mysqli_real_escape_string($con, $_POST['applied']);
  $address = mysqli_real_escape_string($con, $_POST['address']);
  $state = mysqli_real_escape_string($con, $_POST['state']);
  $pincode = mysqli_real_escape_string($con, $_POST['pincode']);
  $aadhaarcard = mysqli_real_escape_string($con, $_POST['aadhaarcard']);
  $aviation = mysqli_real_escape_string($con, $_POST['aviation']);
  $created_at = date('Y-m-d h:i:s');
  // $photo = test_input(($con,$_Files['photo']);



  // email format check and not matched in the database 
  $emailQuery = "Select * from career where email = '$email'";
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
  //email end

  //Aadhaar Card Validation and Number Format
  $aadhaarCardQuery = "Select * from career where aadhaarcard = '$aadhaarcard'";
  $aadhaarCardQueryCheck = mysqli_query($con, $aadhaarCardQuery);

  if (mysqli_num_rows($aadhaarCardQueryCheck) > 0) {
    $aadhaarErr = "This Aadhaar Card is Already Register";
    $flag = false;
  } else {
    if (!preg_match('/^[0-9]{12,12}$/', $aadhaarcard)) {
      $aadhaarErr = "Aadhaar card must be in Numeric and 12 Digit";
      $flag = false;
    } else {
      if (strlen($aadhaarcard) > 12) {
        $aadhaarErr = "please put a valid 12 digit aadhar card number";
        $flag = false;
      }
    }
  }
  //aadharcard end

  //Name and Father name validation

  if (!preg_match("/^[a-zA-Z'-]+$/", $name)) {
    $nameErr = "only alphabet and white space allowed";
    $flag = false;
  }

  if (!preg_match("/^[a-zA-Z'-]+$/", $fathername)) {
    $fatherErr = "only alphabet and white space allowed";
    $flag =  false;
  }

  // Name and Father name validation end

  //number validation
  if (!preg_match('/^[0-9]{10,10}+$/', $contact)) {
    $contactErr = "Invalid Phone Number";
    $flag = false;
  }
  //number validation end

  //pincode validaiton
  if (!preg_match('/^[0-9]{6,6}+$/', $pincode)) {
    $pincodeErr = "pincode must be in numeric";
    $flag = false;
  }
  //pincode validation end 

  //Photo validation



  $allowed_extension = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];


  if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $extension_1 = explode('.', $_FILES['photo']['name']);
    $file_name = $extension_1[0] . '_' . time() . '.' . $extension_1[1];
    $destination_folder = 'documents/';
    $photoProperPath = $destination_folder . $file_name;

    if (in_array($extension_1[1], $allowed_extension)) {

      move_uploaded_file($_FILES['photo']['tmp_name'], $photoProperPath);
    } else {
      $photoErr = 'jpg, jpeg, and png file format are allowed';
      $flag = false;
    }
  } else {
    //check file size 10mb 

    if ($_FILES["photo"]["size"] > 10485760) {
      $photoErr = "Sorry, your file is too large.";
      $flag = false;
    }
  }

  //Photo validation end


  //aviation doc
  $allowed_extension2 = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];


  if (isset($_FILES['aviationphoto']) && $_FILES['aviationphoto']['error'] == 0) {
    $extension_2 = explode('.', $_FILES['aviationphoto']['name']);
    $file_name2 = $extension_2[0] . '_' . time() . '.' . $extension_2[1];
    $destination_folder = 'documents/';
    $aviationProperPath = $destination_folder . $file_name2;

    if (in_array($extension_2[1], $allowed_extension2)) {

      move_uploaded_file($_FILES['aviationphoto']['tmp_name'], $aviationProperPath);
    } else {
      $aviationErr = 'pdf, docx, jpg, jpeg, and png file format are allowed';
      $flag = false;
    }
  } 
     else {
    //check file size 10mb 

    if ($_FILES["aviationphoto"]["size"] > 10485760) {
      $aviationErr = "Sorry, your file is too large.";
      $flag = false;
    }
  }

  //avaition doc end





  // error and final submission
  if ($flag && isset($_POST['declare'])) {
    $insertCareerData = "INSERT INTO career(name, fathername, contact, email, dob, gender, education, applied, address, state, pincode, aadhaarcard, photo,admission_code,aviation_doc,created_at) VALUES ('$name','$fathername','$contact','$email','$dob','$gender','$education','$applied','$address','$state','$pincode','$aadhaarcard','$photoProperPath','$aviation','$aviationProperPath','$created_at')";
    $result = mysqli_query($con, $insertCareerData);
    if ($result) {

      //email send code
      $to = 'hr@shineairways.com';
      $subject = "Online Admission Enquiry Notification";
      $message = "
            <html>
            <head>
              <title>'$subject'</title>
            </head>
            <body>
              <h1 style='color: orange; text-align: center;'> ShineAirways</h1>
              <hr>
              <p style='text-align: center; font-size: 20px; font-weight: bold;'>Applied For: $applied</p>
              <hr>
              <p>Name: $name</p>
              <hr>

              <p>Name: $fathername</p>
              <hr>
              <p>Email: $email</p>
              <hr>
              <p>Contact: $contact</p>
              <hr>
              <p>D.O.B: $dob</p>
              <hr>
              <p>Gender: $gender</p>
              <hr>
              <p>Education: $education</p>
              <hr>
              <p>Address: $address</p>
              <hr>
              <p>State: $state</p>
              <hr>
              <p>Pincode: $pincode</p>
              <hr>
              <p>AadharCard: $aadhaarcard</p>
              <hr>
              <hr>
              <p>Admission Code: $aviation</p>
              <hr>
              <span>Photo: <a href='dilip.jrinfotechs.com/career/$photoProperPath' download >$name</a></span>
              <hr>
              <hr>
              <span>Photo: <a href='dilip.jrinfotechs.com/career/$aviationProperPath' download >$name</a></span>
              <hr>
            </body>
            </html>
            ";
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= 'From: <' . $email . '>' . "\r\n";
      $mail = mail($to, $subject, $message, $headers);


      $to1 = $email;
      $subject1 = "ShineAirways Registration";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />



</head>


<body>
    <!-- navbar -->
    <section id="navbar-mains" style="position:sticky; top:0; z-index:9999; ">
        <nav class="navbar navbar-expand-lg navbar-light bg-light p-sticky" style="position: relative; z-index: 999;">
            <div class="container-fluid">
                <a class="navbar-brand nav-logo" href="#"><img
                        src="https://shineairways.com/wp-content/uploads/2022/05/Shine-Airways-Final-Plane-Logo.png"
                        style="width: 120px; height: auto;"> </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
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
                    <button class="m-sm-0"
                        style=" background-color: rgb(66, 100, 248);  color:rgb(240, 236, 236); border: 0px;   border-radius: 5px;; padding: 11px; font-size: 14px;">Login
                        or Create Account</button>
                </div>

            </div>
            </div>
            </div>
        </nav>
    </section>
    <!-- navbar -->

    <div class="container-fluid border">
        <div class="row">
            <div class="col-md-6" style="background-color: #003a6c; color: white; text-align: center">
                <div class="mt-3" style="width: 50%; margin: auto">
                    <img src="forms_docs.png" style="width: 100%" alt="" />
                </div>
                <div style="width: 60%; margin:auto; text-align: center;">
                    <h1>We are Hiring</h1>
                    <p style="color:white">
                        We offer an outstanding to join a team that takes pride in doing
                        business with people, for people, Shine Airways is a place where
                        you’ll experience great job satisfaction in an inspiring,
                        uplifting work environment.
                    </p>
                </div>
                <div style="width: 90%; margin: auto">
                    <img src="plane.jpg" style="width: 100%" alt="" />
                </div>
                <div class="mt-2" style="width: 70%; margin:auto; text-align: center;">
                    <h1>Contact Us</h1>
                    <p style="color:white">
                        Mobile: +91 88606 91383 | Email: info@shineairways.com
                    </p>
                    <p style="font-size:15px; color: white;">Address: G-69, Sector-63, Noida Gautam
                        Buddh Nagar, Uttar Pradesh – 201301
                    </p>

                </div>

            </div>
            <div class="col-md-6 border">

                <div class="container p-2">

                    <form class="row g-3 needs-validation" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                        method="POST" enctype="multipart/form-data">
                        <div class="col-md-12 text-center">
                            <h2> ShineAirways </h2>
                            <p class="">Apply Now</p>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>"
                                placeholder="Full Name" required>
                            <span style="color:red"><?php if (isset($nameErr)) echo $nameErr ?></span>
                        </div>
                        <div class="col-md-6">
                            <label for="fathername" class="form-label">Father Name<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="text" name="fathername" class="form-control" value="<?php echo $fathername; ?>"
                                placeholder="Father Name" required>
                            <span style="color:red"><?php if (isset($fatherErr)) echo $fatherErr ?></span>
                        </div>

                        <div class="col-6">
                            <label for="contact" class="form-label">Contact<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="text" class="form-control" name="contact" placeholder="8123456789"
                                maxlength="10" value="<?php echo $contact; ?>" required>
                            <span class="error"> <?php if (isset($contactErr)) echo $contactErr ?></span>
                        </div>
                        <div class="col-6">
                            <label for="Email" class="form-label">Email<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="email" class="form-control" name="email" placeholder="info@shineairways.com"
                                value="<?php echo $email; ?>" required>
                            <span style="color:red"><?php if (isset($emailErr)) echo $emailErr ?></span>
                        </div>
                        <div class="col-6">
                            <label for="dob" class="form-label">DOB<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="date" class="form-control" name="dob" required>

                        </div>

                        <div class="col-6">
                            <label for="gender" class="form-label">Gender<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <select id="inputState" class="form-select" name="gender" value="<?= $gender; ?>" required>
                                <option seletcted value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Others">Ohters</option>
                            </select>

                        </div>
                        <div class="col-6">
                            <label for="education" class="form-label">Education<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <select id="inputState" class="form-select" name="education" value="<?= $education; ?>"
                                required>
                                <option seletcted value="10th">10th</option>
                                <option value="12th">12th</option>
                                <option value="Graduation">Graduation</option>
                                <option value="Post Graduation">Post Graduation</option>
                                <option value="Others">Ohters</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="applied" class="form-label">Applied For<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <select id="inputState" class="form-select" name="applied" required>
                                <option seletcted value="Travel Manager">Travel Manager</option>
                                <option value="Project Manager">Project Manager</option>
                                <option value="Tourism Manager">Tourism Manager</option>
                                <option value="Sales Manager">Sales Manager</option>
                                <option value="Area Manager">Area Manager</option>
                                <option value="Assistant Branch Manager">Assistant Branch Manager</option>
                                <option value="Hr Executive">Hr Executive</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Sales Executive">Sales Executive</option>
                                <option value="IT Manager">IT Manager</option>
                                <option value="HR Manager">HR Manager</option>
                                <option value="PSA">PSA</option>
                                <option value="CSA">CSA</option>
                                <option value="Travel & Tourism">Travel & Tourism</option>
                                <option value="Cabin  Crew">Cabin Crew</option>
                                <option value="Ticket Support Executive">Ticket Support Executive</option>
                                <option value="Customer Realtionship Manager">Customer Realtionship Manager</option>


                            </select>
                        </div>
                        <div class="col-6">
                            <label for="address" class="form-label">Address<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="text" class="form-control" name="address" placeholder="Address"
                                value="<?= $pincode ?>" required>
                            <span class="error"> <?php if (isset($pincodeErr)) echo $pincodeErr ?></span>

                        </div>
                        <div class="col-6">
                            <label for="state" class="form-label">States<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <select name="state" id="state" class="form-select" required>
                                <option value="" selected disabled>Select a state</option>
                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                <option value="Assam">Assam</option>
                                <option value="Bihar">Bihar</option>
                                <option value="Chhattisgarh">Chhattisgarh</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Goa">Goa</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Haryana">Haryana</option>
                                <option value="Himachal Pradesh">Himachal Pradesh</option>
                                <option value="Jharkhand">Jharkhand</option>
                                <option value="Karnataka">Karnataka</option>
                                <option value="Kerala">Kerala</option>
                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                <option value="Maharashtra">Maharashtra</option>
                                <option value="Manipur">Manipur</option>
                                <option value="Meghalaya">Meghalaya</option>
                                <option value="Mizoram">Mizoram</option>
                                <option value="Nagaland">Nagaland</option>
                                <option value="Odisha">Odisha</option>
                                <option value="Punjab">Punjab</option>
                                <option value="Rajasthan">Rajasthan</option>
                                <option value="Sikkim">Sikkim</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Telangana">Telangana</option>
                                <option value="Tripura">Tripura</option>
                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                <option value="Uttarakhand">Uttarakhand</option>
                                <option value="West Bengal">West Bengal</option>
                            </select>
                        </div>

                        <div class="col-6">

                            <label for="pincode" class="form-label">Pincode<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="text" class="form-control" maxlength="6" name="pincode" placeholder="Pincode"
                                required value="<?= $pincode; ?>">
                            <span class="error"> <?php if (isset($pincodeErr)) echo $pincodeErr ?></span>

                        </div>

                        <div class="col-md-6">
                            <label for="aadharcard" class="form-label">Aadhaar No<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="text" class="form-control" id="aadharcard" name="aadhaarcard" maxlength="12"
                                value="<?= $aadhaarcard ?>" placeholder="eg: 845875415263" required>
                            <span class="error"> <?php if (isset($aadhaarErr)) echo $aadhaarErr ?></span>

                        </div>

                        <div class="col-md-6">
                            <label for="avaition" class="form-label">Admission Code<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="text" class="form-control" name="aviation" value="<?= $aviation ?>"
                                placeholder="eg: BA5T5E7Y" required>
                        </div>


                        <div class="col-md-6">
                            <label for="photoupload" class="form-label">Aviation Certificate Upload<span
                                    class="text-danger"><strong> *</strong></span> </label><br>
                            <input type="file" class="form-control" id="inputGroupFile01" name="aviationphoto" required>
                            <span class="error"><?php if (isset($aviationErr)) echo $aviationErr ?></span>
                        </div>
                        <div class="col-md-6">
                            <label for="photoupload" class="form-label">Photo<span class="text-danger"><strong>
                                        *</strong></span> </label><br>
                            <input type="file" class="form-control" id="inputGroupFile01" name="photo" required>
                            <span class="error"><?php if (isset($photoErr)) echo $photoErr ?></span>
                        </div>
                        <div class="col-md-12 alert alert-primary">
                            <h3>Declaration / घोषणा</h3>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="declare" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    I Declare that the name, class, date of birth, address and other information given
                                    by me in the online application form is correct to the best of my knowledge and
                                    belief. Which I declare to be truely correct. If the above information is found
                                    incomplete or incorrect, my candidature is liable to be terminated at any time.
                                </label>

                            </div>
                        </div>


                        <div class="col-12">
                            <button type="submit" name="submit" class="btn btn-primary">SUBMIT</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
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
                            <li><i class="bx bx-chevron-right"></i> <a style="color:white" ;
                                    href="../index.html">Home</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a style="color:white" ; href="../about.html">About
                                    us</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a style="color:white" ;
                                    href="../product.html">Product</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a style="color:white" ;
                                    href="../contact.html">Contact Us</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links" style="color:white;">
                        <h4 style="color:white;">Our Products</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a style="color: white;"
                                    href="../privacypolicy.html">Privacy Policy</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a style="color: white;"
                                    href="../terms&condition.html">Terms & Conditions</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a style="color: white;" href="../about.html">About
                                    Us</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a style="color: white;"
                                    href="../ourservices.html">Services</a></li>
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
                        &copy; Copyright <strong><span style="color:orange">Shine Airways</span> 2019</strong>. All
                        Rights Reserved
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

    <!-- <img src="../images/shine-logo.png" alt=""> -->
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
</script>

<!-- <script src="./script.js" type="module"></script> -->
<script src="../script.js" type="module"></script>
<script src="../assets/js/faq.js"></script>