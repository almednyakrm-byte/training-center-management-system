<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(طلابRepository::class);
        $this->controller = new طلابController($this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = ['طلاب' => ['id' => 1, 'name' => 'Student 1']];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$expectedResponse['طلاب']]);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOne()
    {
        $expectedResponse = ['id' => 1, 'name' => 'Student 1'];
        $this->repository->expects($this->once())
            ->method('findOne')
            ->with(1)
            ->willReturn($expectedResponse);

        $response = $this->controller->getOne(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOneNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('findOne')
            ->with(1)
            ->willReturn(null);

        $this->controller->getOne(1);
    }

    public function testCreate()
    {
        $expectedResponse = ['id' => 1, 'name' => 'Student 1'];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($expectedResponse)
            ->willReturn($expectedResponse);

        $request = new Request();
        $request->request->set('name', 'Student 1');
        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate()
    {
        $expectedResponse = ['id' => 1, 'name' => 'Student 1'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, $expectedResponse)
            ->willReturn($expectedResponse);

        $request = new Request();
        $request->request->set('name', 'Student 1');
        $response = $this->controller->update(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Student 1'])
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', 'Student 1');
        $this->controller->update(1, $request);
    }

    public function testDelete()
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// Entity\طلاب.php

namespace App\Entity;

class طلاب
{
    private $id;
    private $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}



// Controller\طلابController.php

namespace App\Controller;

use App\Repository\طلابRepository;
use App\Entity\طلاب;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class طلابController
{
    private $repository;

    public function __construct(طلابRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        $طلاب = $this->repository->findAll();
        return new Response(json_encode($طلاب), Response::HTTP_OK);
    }

    public function getOne($id)
    {
        $طلاب = $this->repository->findOne($id);
        if (!$طلاب) {
            throw new NotFoundHttpException('طلاب not found');
        }
        return new Response(json_encode($طلاب), Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $طلاب = new طلاب();
        $طلاب->setName($request->request->get('name'));
        $this->repository->create($طلاب);
        return new Response(json_encode($طلاب), Response::HTTP_CREATED);
    }

    public function update($id, Request $request)
    {
        $طلاب = $this->repository->findOne($id);
        if (!$طلاب) {
            throw new NotFoundHttpException('طلاب not found');
        }
        $طلاب->setName($request->request->get('name'));
        $this->repository->update($id, $طلاب);
        return new Response(json_encode($طلاب), Response::HTTP_OK);
    }

    public function delete($id)
    {
        $this->repository->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}



// Repository\طلابRepository.php

namespace App\Repository;

use App\Entity\طلاب;

interface طلابRepository
{
    public function findAll();
    public function findOne($id);
    public function create(طلاب $طلاب);
    public function update($id, طلاب $طلاب);
    public function delete($id);
}



// Repository\MockطلابRepository.php

namespace App\Repository;

use App\Entity\طلاب;

class MockطلابRepository implements طلابRepository
{
    public function findAll()
    {
        // Return a list of طلاب objects
    }

    public function findOne($id)
    {
        // Return a single طلاب object
    }

    public function create(طلاب $طلاب)
    {
        // Create a new طلاب object
    }

    public function update($id, طلاب $طلاب)
    {
        // Update an existing طلاب object
    }

    public function delete($id)
    {
        // Delete a طلاب object
    }
}