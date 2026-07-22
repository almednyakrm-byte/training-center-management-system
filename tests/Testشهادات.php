<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\شهاداتController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use PHPUnit\Framework\MockObject\MockBuilder;

class Testشهادات extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $pdo;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new شهاداتController($this->router, $this->tokenStorage, $this->pdo);
    }

    public function testGet()
    {
        $request = new Request();
        $request->attributes->set('_route', 'شهادات_get');
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM شهادات')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->get($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPost()
    {
        $request = new Request();
        $request->request->set('name', 'Test Certificate');
        $request->request->set('description', 'This is a test certificate');
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO شهادات (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('commit');

        $response = $this->controller->post($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPut()
    {
        $request = new Request();
        $request->attributes->set('id', 1);
        $request->request->set('name', 'Updated Certificate');
        $request->request->set('description', 'This is an updated certificate');
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE شهادات SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('commit');

        $response = $this->controller->put($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $request = new Request();
        $request->attributes->set('id', 1);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM شهادات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('commit');

        $response = $this->controller->delete($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'شهادات' module. It creates a mock object for the PDO statement and uses it to simulate the database operations. The test methods cover the GET, POST, PUT, and DELETE requests.