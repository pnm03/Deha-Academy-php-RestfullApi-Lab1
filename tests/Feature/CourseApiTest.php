<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Response;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_courses(): void
    {
        Course::factory()->count(3)->create();

        $response = $this->getJson('/api/courses');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'description', 'start_date']
            ]);
    }

    public function test_create_course(): void
    {
        $data = [
            'name' => 'Laravel Basics',
            'description' => 'Fundamental Laravel course',
            'start_date' => '2024-06-01'
        ];

        $response = $this->postJson('/api/courses', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('name', $data['name'])
            ->assertJsonStructure([
                'id', 'name', 'description', 'start_date'
            ]);

        $this->assertDatabaseHas('courses', $data);
    }

    public function test_show_non_existent_course(): void
    {
        $response = $this->getJson('/api/courses/0'); // Sử dụng ID không tồn tại
        
        $response->assertStatus(404);
    }
}