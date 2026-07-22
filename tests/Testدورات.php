<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Course;
use App\Repository\CourseRepository;
use App\Controller\CourseController;
use App\Service\CourseService;

class Testدورات extends TestCase
{
    private $courseController;
    private $courseService;
    private $courseRepository;
    private $mockPDO;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock('PDO');
        $this->courseRepository = $this->createMock(CourseRepository::class);
        $this->courseService = $this->createMock(CourseService::class);
        $this->courseController = new CourseController($this->courseService, $this->courseRepository);
    }

    public function testGetCourses()
    {
        $courses = [
            new Course('Course 1'),
            new Course('Course 2'),
        ];

        $this->courseRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($courses);

        $response = $this->courseController->getCourses();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($courses), $response->getContent());
    }

    public function testGetCourse()
    {
        $course = new Course('Course 1');

        $this->courseRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);

        $response = $this->courseController->getCourse(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testCreateCourse()
    {
        $course = new Course('Course 1');
        $course->setId(1);

        $this->courseService->expects($this->once())
            ->method('createCourse')
            ->with($course)
            ->willReturn($course);

        $response = $this->courseController->createCourse($course);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testUpdateCourse()
    {
        $course = new Course('Course 1');
        $course->setId(1);

        $this->courseService->expects($this->once())
            ->method('updateCourse')
            ->with($course)
            ->willReturn($course);

        $response = $this->courseController->updateCourse(1, $course);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testDeleteCourse()
    {
        $course = new Course('Course 1');
        $course->setId(1);

        $this->courseService->expects($this->once())
            ->method('deleteCourse')
            ->with($course)
            ->willReturn(true);

        $response = $this->courseController->deleteCourse(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGetCoursesNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->courseRepository->expects($this->once())
            ->method('findAll')
            ->willReturn(null);

        $this->courseController->getCourses();
    }

    public function testGetCourseNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->courseRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->courseController->getCourse(1);
    }

    public function testCreateCourseValidation()
    {
        $course = new Course('');

        $this->courseService->expects($this->never())
            ->method('createCourse');

        $response = $this->courseController->createCourse($course);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUpdateCourseValidation()
    {
        $course = new Course('');

        $this->courseService->expects($this->never())
            ->method('updateCourse');

        $response = $this->courseController->updateCourse(1, $course);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

*   `testGetCourses`: Tests the GET request for retrieving all courses.
*   `testGetCourse`: Tests the GET request for retrieving a single course by ID.
*   `testCreateCourse`: Tests the POST request for creating a new course.
*   `testUpdateCourse`: Tests the PUT request for updating an existing course.
*   `testDeleteCourse`: Tests the DELETE request for deleting a course.
*   `testGetCoursesNotFound`: Tests the GET request for retrieving all courses when no courses are found.
*   `testGetCourseNotFound`: Tests the GET request for retrieving a single course by ID when the course is not found.
*   `testCreateCourseValidation`: Tests the POST request for creating a new course with invalid data.
*   `testUpdateCourseValidation`: Tests the PUT request for updating an existing course with invalid data.

Each test method uses the `createMock` method to create mock objects for the `CourseRepository` and `CourseService` classes. The `expects` method is used to specify the expected behavior of the mock objects. The `willReturn` method is used to specify the return value of the mock objects.

The test methods also use the `assertEquals` method to verify that the response status code and content match the expected values. The `expectException` method is used to verify that the correct exception is thrown when a course is not found.