<?php

namespace App\Tests\Controller;

use App\Controller\ProfesseursController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestProfesseurs extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new ProfesseursController($this->pdoMock);
    }

    public function testGetProfesseurs(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM professeurs')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getProfesseurs();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostProfesseurs(): void
    {
        $request = new Request([], [], ['json' => ['nom' => 'John', 'prenom' => 'Doe']]);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO professeurs (nom, prenom) VALUES (:nom, :prenom)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $this->controller->postProfesseurs($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutProfesseurs(): void
    {
        $request = new Request([], [], ['json' => ['id' => 1, 'nom' => 'John', 'prenom' => 'Doe']]);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE professeurs SET nom = :nom, prenom = :prenom WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $this->controller->putProfesseurs($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteProfesseur(): void
    {
        $request = new Request([], [], ['id' => 1]);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM professeurs WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $this->controller->deleteProfesseur($request);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'مدرسين' module. It uses mocked PDO statements to simulate database interactions. The tests verify that the correct HTTP status codes are returned for each operation.