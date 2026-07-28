<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\HttpFoundation\Session\Session;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $connectionMock;
    private $sessionMock;

    protected function setUp(): void
    {
        $this->connectionMock = $this->createMock(Connection::class);
        $this->sessionMock = $this->createMock(Session::class);
        $this->authRepository = new AuthRepository($this->connectionMock);
        $this->authService = new AuthService($this->authRepository, $this->sessionMock);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';
        $expectedUser = new User($username, $password);

        $this->connectionMock->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn([$expectedUser->toArray()]);

        $this->authService->login($username, $password);

        $this->assertTrue($this->sessionMock->has('user'));
        $this->assertEquals($expectedUser, $this->sessionMock->get('user'));
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'wrongpassword';

        $this->connectionMock->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn([]);

        $this->authService->login($username, $password);

        $this->assertFalse($this->sessionMock->has('user'));
    }

    public function testRegisterSuccess()
    {
        $username = 'newuser';
        $password = 'newpassword';

        $this->connectionMock->expects($this->once())
            ->method('insert')
            ->with('users', ['username' => $username, 'password' => $password]);

        $this->authService->register($username, $password);

        $this->assertTrue($this->connectionMock->hasExecuted('insert'));
    }

    public function testRegisterFailure()
    {
        $username = 'newuser';
        $password = 'newpassword';

        $this->connectionMock->expects($this->once())
            ->method('insert')
            ->with('users', ['username' => $username, 'password' => $password])
            ->willThrowException(new \Exception('Database error'));

        $this->expectException(\Exception::class);

        $this->authService->register($username, $password);
    }
}


This test file covers the following scenarios:

1.  **Login Success**: Tests that the `login` method successfully logs in a user with the correct credentials.
2.  **Login Failure**: Tests that the `login` method fails to log in a user with incorrect credentials.
3.  **Register Success**: Tests that the `register` method successfully registers a new user.
4.  **Register Failure**: Tests that the `register` method fails to register a new user due to a database error.

Each test case uses PHPUnit's mocking features to isolate the dependencies of the `AuthService` class and focus on the behavior of the `login` and `register` methods.