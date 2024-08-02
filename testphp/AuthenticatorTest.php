// tests/AuthenticatorTest.php
use PHPUnit\Framework\TestCase;

class AuthenticatorTest extends TestCase {
    private $conn;
    private $authenticator;

    protected function setUp(): void {
        // Mock the database connection
        $this->conn = $this->createMock(mysqli::class);

        // Instantiate the Authenticator class with the mocked connection
        $this->authenticator = new Authenticator($this->conn);
    }

    public function testValidUserAuthentication() {
        // Mock email and password
        $email = 'user@example.com';
        $password = 'password';

        // Mock query result
        $queryResult = $this->createMock(mysqli_result::class);
        $queryResult->method('num_rows')->willReturn(1);
        $queryResult->method('fetch_assoc')->willReturn([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'user_type' => 'user'
        ]);

        // Mock the connection behavior
        $this->conn->method('query')->willReturn($queryResult);

        // Perform the authentication
        $user = $this->authenticator->authenticate($email, $password);

        // Assert the result
        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user['name']);
    }

    public function testInvalidUserAuthentication() {
        // Mock email and password
        $email = 'invalid@example.com';
        $password = 'wrongpassword';

        // Mock query result
        $queryResult = $this->createMock(mysqli_result::class);
        $queryResult->method('num_rows')->willReturn(0);

        // Mock the connection behavior
        $this->conn->method('query')->willReturn($queryResult);

        // Perform the authentication
        $user = $this->authenticator->authenticate($email, $password);

        // Assert the result
        $this->assertNull($user);
    }
}
