<?php
session_start();

$servername = "localhost";
$username = "slop";
$password = "slop";
$dbname = "brukere";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("php eksplosjon, never forget: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $brukernavn = trim($_POST['brukernavn']);
    $epost = trim($_POST['epost']);
    $passord = $_POST['passord'];

   
    if (empty($brukernavn) || empty($epost) || empty($passord)) {
        $message = "forbanna teksten";
    } else {

        
        $check = $conn->prepare("SELECT id FROM bruker WHERE epost = ?");
        $check->bind_param("s", $epost);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "epost allerede registrert, moron";
        } else {
// hasher passord isteder for å lagre det i plaintext, dette er viktig i tilfelle av et databaseangrep eller lingende smile
            $hashed_passord = password_hash($passord, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO bruker (brukernavn, epost, passord) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $brukernavn, $epost, $hashed_passord);

            if ($stmt->execute()) {
                $message = "registrasjon fullført.";
            } else {
                $message = "uh oooh stinkyyy."; //noe gikk galt
            }

            $stmt->close();
        }

        $check->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css.css">
</head>
<body>

<h1>💨🔥 FART LOVER 69!!! 💨🔥</h1>

<h2>Register</h2>
<p style="color:red;"><?php echo $message; ?></p>

<form method="post">
  Username: <input type="text" name="brukernavn"><br><br>
  Email: <input type="email" name="epost"><br><br>
  Password: <input type="password" name="passord"><br><br>
  <input type="submit" value="Register">
</form>

<a href="login.php">Go to Login</a>

<br>

<video controls width="500px">
        <source src="Kunst/rPYNKb3b.mov" \>
        </video>

</body>
</html>
