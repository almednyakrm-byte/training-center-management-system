<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock('PDO');
        $this->repository = $this->createMock(طلابRepository::class);
        $this->controller = new طلابController($this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetById()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new طلاب());

        $response = $this->controller->getById(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPost()
    {
        $expectedResponse = new JsonResponse(['message' => 'Student created successfully']);
        $student = new طلاب();
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO students (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock('PDOStatement'));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $student->getName(), 'email' => $student->getEmail()]);

        $response = $this->controller->post($student);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPut()
    {
        $expectedResponse = new JsonResponse(['message' => 'Student updated successfully']);
        $student = new طلاب();
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE students SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $student->getName(), 'email' => $student->getEmail(), 'id' => $student->getId()]);

        $response = $this->controller->put($student);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $expectedResponse = new JsonResponse(['message' => 'Student deleted successfully']);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM students WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response);
    }
}


This test file covers the CRUD operations for the 'طلاب' module. It uses mocked PDO statements to simulate database interactions. The tests cover the following scenarios:

- `testGetAll`: Tests the `getAll` method of the controller, which retrieves all students from the database.
- `testGetById`: Tests the `getById` method of the controller, which retrieves a student by ID from the database.
- `testPost`: Tests the `post` method of the controller, which creates a new student in the database.
- `testPut`: Tests the `put` method of the controller, which updates an existing student in the database.
- `testDelete`: Tests the `delete` method of the controller, which deletes a student from the database.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you will need to implement the `طلابController` and `طلابRepository` classes to make this test file work.