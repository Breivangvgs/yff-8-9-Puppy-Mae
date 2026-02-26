<?php
session_start();

$servername = "localhost";
$username = "slop";
$password = "slop";
$dbname = "brukere";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $epost = trim($_POST['epost']);
    $passord = $_POST['passord'];

    $stmt = $conn->prepare("SELECT id, brukernavn, passord FROM bruker WHERE epost = ?");
    $stmt->bind_param("s", $epost);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($passord, $user['passord'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['brukernavn'] = $user['brukernavn'];

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "nuh uh"; //feil passord
        }

    } else {
        $message = "du har ikke en email dumdum";
    }

    $stmt->close();
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
<p style="color:red;"><?php echo $message; ?></p>

<form method="post">
  Epost: <input type="email" name="epost"><br><br>
  Passord: <input type="password" name="passord"><br><br>
  <input type="submit" value="Login">
</form>

<a href="register.php">Lag bruker</a>
<br> 
<img src="Kunst/IMG_6915.png" width="1000px"/>

</body>
</html>
