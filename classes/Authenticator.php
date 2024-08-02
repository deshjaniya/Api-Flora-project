// Authenticator.php
class Authenticator {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function authenticate($email, $password) {
        $filter_email = filter_var($email, FILTER_SANITIZE_STRING); 
        $email = mysqli_real_escape_string($this->conn, $filter_email);
        $filter_pass = filter_var($password, FILTER_SANITIZE_STRING); 
        $pass = mysqli_real_escape_string($this->conn, md5($filter_pass)); 

        $query = "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'";
        $result = mysqli_query($this->conn, $query);

        if (!$result) {
            throw new Exception('Query failed');
        }

        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }
}
