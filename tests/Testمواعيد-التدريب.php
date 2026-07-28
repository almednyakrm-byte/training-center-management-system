<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمواعيدالتدريب extends TestCase
{
    private $pdo;
    private $mockStatement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);
    }

    public function testGetAllمواعيدالتدريب()
    {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'مواعيد التدريب 1'],
                ['id' => 2, 'name' => 'مواعيد التدريب 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مواعيد_التدريب')
            ->willReturn($this->mockStatement);

        $result = $this->getAllمواعيدالتدريب($this->pdo);
        $this->assertCount(2, $result);
    }

    public function testGetمواعيدالتدريبById()
    {
        $id = 1;

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([$id]);

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => $id, 'name' => 'مواعيد التدريب 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مواعيد_التدريب WHERE id = ?')
            ->willReturn($this->mockStatement);

        $result = $this->getمواعيدالتدريبById($this->pdo, $id);
        $this->assertEquals($id, $result['id']);
    }

    public function testCreateمواعيدالتدريب()
    {
        $data = ['name' => 'مواعيد التدريب 3'];

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مواعيد_التدريب (name) VALUES (?)')
            ->willReturn($this->mockStatement);

        $result = $this->createمواعيدالتدريب($this->pdo, $data);
        $this->assertTrue($result);
    }

    public function testUpdateمواعيدالتدريب()
    {
        $id = 1;
        $data = ['name' => 'مواعيد التدريب 1 updated'];

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with(array_merge([$id], $data));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مواعيد_التدريب SET name = ? WHERE id = ?')
            ->willReturn($this->mockStatement);

        $result = $this->updateمواعيدالتدريب($this->pdo, $id, $data);
        $this->assertTrue($result);
    }

    public function testDeleteمواعيدالتدريب()
    {
        $id = 1;

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([$id]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مواعيد_التدريب WHERE id = ?')
            ->willReturn($this->mockStatement);

        $result = $this->deleteمواعيدالتدريب($this->pdo, $id);
        $this->assertTrue($result);
    }

    private function getAllمواعيدالتدريب(PDO $pdo)
    {
        $stmt = $pdo->prepare('SELECT * FROM مواعيد_التدريب');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function getمواعيدالتدريبById(PDO $pdo, int $id)
    {
        $stmt = $pdo->prepare('SELECT * FROM مواعيد_التدريب WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function createمواعيدالتدريب(PDO $pdo, array $data)
    {
        $stmt = $pdo->prepare('INSERT INTO مواعيد_التدريب (name) VALUES (?)');
        return $stmt->execute($data);
    }

    private function updateمواعيدالتدريب(PDO $pdo, int $id, array $data)
    {
        $stmt = $pdo->prepare('UPDATE مواعيد_التدريب SET name = ? WHERE id = ?');
        return $stmt->execute(array_merge([$id], $data));
    }

    private function deleteمواعيدالتدريب(PDO $pdo, int $id)
    {
        $stmt = $pdo->prepare('DELETE FROM مواعيد_التدريب WHERE id = ?');
        return $stmt->execute([$id]);
    }
}