<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\مراكز التدريبController;
use App\Repository\مراكز التدريبRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testمراكز التدريب extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(مراكز التدريبRepository::class);
        $this->controller = new مراكز التدريبController($this->repository);
    }

    public function testGetAll()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'مركز التدريب 1'],
                ['id' => 2, 'name' => 'مركز التدريب 2'],
            ]);

        $response = $this->controller->getAll();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetById()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(['id' => $id, 'name' => 'مركز التدريب 1']);

        $response = $this->controller->getById($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $data = ['name' => 'مركز التدريب الجديد'];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn(['id' => 1, 'name' => 'مركز التدريب الجديد']);

        $response = $this->controller->create($data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'مركز التدريب المعدل'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(['id' => $id, 'name' => 'مركز التدريب المعدل']);

        $response = $this->controller->update($id, $data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->controller->delete($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetAll`: Tests the `getAll` method to ensure it returns a JSON response with a 200 status code.
- `testGetById`: Tests the `getById` method to ensure it returns a JSON response with a 200 status code.
- `testCreate`: Tests the `create` method to ensure it returns a JSON response with a 201 status code.
- `testUpdate`: Tests the `update` method to ensure it returns a JSON response with a 200 status code.
- `testDelete`: Tests the `delete` method to ensure it returns a JSON response with a 204 status code.

Note that this test file assumes that the `مراكز التدريبController` and `مراكز التدريبRepository` classes are already defined and available in the test environment.