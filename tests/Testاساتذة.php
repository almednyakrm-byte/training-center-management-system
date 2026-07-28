<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProfesseurController;
use App\Repository\ProfesseurRepository;
use App\Entity\Professeur;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class TestProfesseur extends TestCase
{
    private $professeurController;
    private $professeurRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->professeurRepository = $this->createMock(ProfesseurRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->professeurController = new ProfesseurController($this->professeurRepository, $this->entityManager);
    }

    public function testGetProfesseurs(): void
    {
        $professeurs = [
            new Professeur('1', 'John Doe'),
            new Professeur('2', 'Jane Doe'),
        ];

        $this->professeurRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($professeurs);

        $response = $this->professeurController->getProfesseurs();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($professeurs), $response->getContent());
    }

    public function testGetProfesseur(): void
    {
        $professeur = new Professeur('1', 'John Doe');

        $this->professeurRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);

        $response = $this->professeurController->getProfesseur('1');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($professeur), $response->getContent());
    }

    public function testGetProfesseurNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->professeurRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->professeurController->getProfesseur('1');
    }

    public function testCreateProfesseur(): void
    {
        $professeur = new Professeur('1', 'John Doe');

        $this->professeurRepository->expects($this->once())
            ->method('save')
            ->with($professeur)
            ->willReturn($professeur);

        $response = $this->professeurController->createProfesseur(new Request(['json' => ['id' => '1', 'name' => 'John Doe']]));

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($professeur), $response->getContent());
    }

    public function testUpdateProfesseur(): void
    {
        $professeur = new Professeur('1', 'John Doe');

        $this->professeurRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);

        $this->professeurRepository->expects($this->once())
            ->method('save')
            ->with($professeur)
            ->willReturn($professeur);

        $response = $this->professeurController->updateProfesseur('1', new Request(['json' => ['id' => '1', 'name' => 'John Doe']]));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($professeur), $response->getContent());
    }

    public function testUpdateProfesseurNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->professeurRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->professeurController->updateProfesseur('1', new Request(['json' => ['id' => '1', 'name' => 'John Doe']]));
    }

    public function testDeleteProfesseur(): void
    {
        $professeur = new Professeur('1', 'John Doe');

        $this->professeurRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);

        $this->professeurRepository->expects($this->once())
            ->method('remove')
            ->with($professeur);

        $response = $this->professeurController->deleteProfesseur('1');

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteProfesseurNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->professeurRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->professeurController->deleteProfesseur('1');
    }
}


This test file covers the following scenarios:

- `testGetProfesseurs`: Tests the GET request to retrieve all professeurs.
- `testGetProfesseur`: Tests the GET request to retrieve a single professeur by ID.
- `testGetProfesseurNotFound`: Tests the GET request to retrieve a non-existent professeur.
- `testCreateProfesseur`: Tests the POST request to create a new professeur.
- `testUpdateProfesseur`: Tests the PUT request to update an existing professeur.
- `testUpdateProfesseurNotFound`: Tests the PUT request to update a non-existent professeur.
- `testDeleteProfesseur`: Tests the DELETE request to delete an existing professeur.
- `testDeleteProfesseurNotFound`: Tests the DELETE request to delete a non-existent professeur.

Note that this test file assumes that the `ProfesseurController` class has the following methods:

- `getProfesseurs`: Handles the GET request to retrieve all professeurs.
- `getProfesseur`: Handles the GET request to retrieve a single professeur by ID.
- `createProfesseur`: Handles the POST request to create a new professeur.
- `updateProfesseur`: Handles the PUT request to update an existing professeur.
- `deleteProfesseur`: Handles the DELETE request to delete an existing professeur.