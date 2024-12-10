<?php
    session_start();
    include ("database.php");
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="style.css" />
    <title>Sign in & Sign up Form</title>
  </head>
  <body>
    <div class="container">
      <div class="mask">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="#" class="sign-in-form" method="post">
            <h2 class="title">Sign in</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Username" name="username" required/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" name="password" required/>
            </div>
            <input type="submit" value="Login" class="btn solid" name="btnLogin"/>
            
          </form>
          <form action="logIn_signUp.php" class="sign-up-form" method="post">
            <h2 class="title">Sign up</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Username" name="signUsername" required />
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" placeholder="Email" name="signEmail" required/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" name="signPswd" required/>
            </div>
            <input type="submit" class="btn" value="Sign up" name="btnRegister" onclick="signUp()"/>
          </form>
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>"WEBSITE NAME"</h3>
            <p>
              PRESERVING THE HERITAGE, PROMOTING THE CULTURE
            </p>
            <button class="btn transparent" id="sign-up-btn">
              Sign up
            </button>
          </div>
         
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>WEBSITE NAME</h3>
            <p>
              IF WE ARE TO PRESERVE CULTURE WE MUST CONTINUE CREATING IT
            </p>
            <button class="btn transparent" id="sign-in-btn">
              Sign in
            </button>
          </div>
        </div>
      </div>
    </div>
    
    
    <script>
       const sign_in_btn = document.querySelector("#sign-in-btn");
    const sign_up_btn = document.querySelector("#sign-up-btn");
    const container = document.querySelector(".container");

    sign_up_btn.addEventListener("click", () => {
      container.classList.add("sign-up-mode");
    });

    sign_in_btn.addEventListener("click", () => {
      container.classList.remove("sign-up-mode");
    });

    function signUp() {
      alert('Account Created. Welcome!!');
      window.location.href = 'search.html'; 
    }

    function signIn() {
      
      const mockDatabase = [
        { username: "user1", password: "p1" },
        { username: "user2", password: "p2" }
        
      ];

      const username = document.getElementById("usernameSignIn").value;
      const password = document.getElementById("passwordSignIn").value;

      // Check if username and password match any in the mock database
      const userFound = mockDatabase.some(user => {
        return user.username === username && user.password === password;
      });

      if (userFound) {
        alert("Welcome Back!");
        window.location.href = 'search.html'; 
        return false; 
      } else {
        alert("Incorrect detail(s)");
        return false; 
      }
    }
    </script>
<?php

// if(isset($_POST["btnRegister"])){
//     $studentID = filter_input(INPUT_POST, "StdId", FILTER_SANITIZE_SPECIAL_CHARS);
//     $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
//     $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

//     // Use prepared statements to prevent SQL injection
//     $sql = "INSERT INTO user (id, username, pswd) VALUES (?, ?, ?)";
//     $stmt = mysqli_prepare($conn, $sql);

//     if($stmt){
//         mysqli_stmt_bind_param($stmt, "iss", $studentID, $username, $password);
//         mysqli_stmt_execute($stmt);
//         echo '<script>alert("The Information is saved")</script>'; 
//     } else {
//         echo "<script>alert('Error: " . mysqli_error($conn) . "')</script>"; 
//     }

//     mysqli_stmt_close($stmt);
    
// }else
if(isset($_POST["btnRegister"])){
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

  // Use prepared statements to prevent SQL injection
  $sql = "INSERT INTO user (id, username, pswd) VALUES (?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  if($stmt){
      mysqli_stmt_bind_param($stmt, "iss", $studentID, $username, $password);
      mysqli_stmt_execute($stmt);
      echo '<script>alert("The Information is saved")</script>'; 
  } else {
      echo "<script>alert('Error: " . mysqli_error($conn) . "')</script>"; 
  }

  mysqli_stmt_close($stmt);
}  


  if(isset($_POST["btnLogin"])){

    $username = filter_input(INPUT_POST,"username",FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST,"password",FILTER_SANITIZE_SPECIAL_CHARS);

    // echo '<script>alert("' . $username . '")</script>'; 
    $sql = "SELECT *
        FROM ORGANIZER
        WHERE oUsername='$username' AND oPswd='$password'";
    $result = mysqli_query($conn,$sql);
    

    try{
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);  
            $_SESSION['username'] = $row['oUsername'];         
            $_SESSION['password'] = $row['oPswd'];
            echo '<script>alert("You are successfully log in!")</script>';
           echo "<script>location.href = 'logIn_signUp.php';</script>";
            exit;
        }else{

          //Performer Database
          $sql = "SELECT *
                  FROM PERFORMER
                  WHERE pUsername=$username AND pPswd=$password";
                  $result = mysqli_query($conn,$sql);

          try{
            if(mysqli_num_rows($result)>0){
                $row = mysqli_fetch_assoc($result);  
                $_SESSION['username'] = $row['pUsername'];         
                $_SESSION['password'] = $row['pPswd'];
                echo '<script>alert("You are successfully log in!")</script>';
               echo "<script>location.href = 'logIn_signUp.php';</script>";
                exit;
            }
        }catch(mysqli_sql_exception $e){
            echo "<script>alert('$e')</script>"; 
        }

            echo '<script>alert("Wrong username or password!")</script>'; 
        }
    }catch(mysqli_sql_exception $e){
        echo "<script>alert('$e')</script>"; 
    }

}




?>

  </body>
</html>
