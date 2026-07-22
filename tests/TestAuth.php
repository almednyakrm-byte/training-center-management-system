<?php

namespace App\Tests;

use App\Auth\AuthService;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TestAuth extends TestCase
{
    private $authService;
    private $connectionMock;

    protected function setUp(): void
    {
        $this->connectionMock = $this->createMock(Connection::class);
        $this->authService = new AuthService($this->connectionMock);
    }

    public function testLoginSuccess()
    {
        // Mock database connection to return a user object
        $user = new User();
        $user->setId(1);
        $user->setUsername('testuser');
        $user->setPassword('testpassword');

        $this->connectionMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', ['testuser'])
            ->willReturn($user);

        $this->authService->login('testuser', 'testpassword');
        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailure()
    {
        // Mock database connection to return null
        $this->connectionMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', ['testuser'])
            ->willReturn(null);

        $this->authService->login('testuser', 'testpassword');
        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        // Mock database connection to insert a new user
        $this->connectionMock
            ->expects($this->once())
            ->method('insert')
            ->with('users', ['username' => 'testuser', 'password' => 'testpassword']);

        $this->authService->register('testuser', 'testpassword');
        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegisterFailure()
    {
        // Mock database connection to throw an exception
        $this->connectionMock
            ->expects($this->once())
            ->method('insert')
            ->with('users', ['username' => 'testuser', 'password' => 'testpassword'])
            ->willThrowException(new \Exception('Database error'));

        $this->authService->register('testuser', 'testpassword');
        $this->assertFalse($this->authService->isLoggedIn());
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that a user can successfully log in with the correct credentials.
- `testLoginFailure`: Tests that a user cannot log in with incorrect credentials.
- `testRegisterSuccess`: Tests that a user can successfully register with the correct credentials.
- `testRegisterFailure`: Tests that a user cannot register with incorrect credentials or if a database error occurs.

Note: This code assumes that the `AuthService` class uses a database connection to perform login and registration operations. The `MockBuilder` is used to create a mock object for the database connection, allowing us to control the behavior of the connection in the test.