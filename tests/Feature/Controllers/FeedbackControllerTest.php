<?php

namespace Tests\Feature\Controllers;

use App\Enums\FeedbackSource;
use App\Models\Feedback;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_create_feedback(): void
    {
        $response = $this->post(route('feedback.store'), [
            'content' => 'lorem ipsum',
            'email' => 'test@test.fr',
        ]);

        $feedback = Feedback::first();

        $this->assertNotNull($feedback);
        $this->assertEquals('lorem ipsum', $feedback->content);
        $this->assertEquals('test@test.fr', $feedback->email);
        $this->assertNull($feedback->user_id);
        $this->assertEquals(FeedbackSource::Dashboard->name, $feedback->source);

        $response->assertRedirectToRoute('feedback');
    }

    public function test_it_create_feedback_with_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('feedback.store'), [
            'content' => 'lorem ipsum'
        ]);

        $feedback = Feedback::first();

        $this->assertNotNull($feedback);
        $this->assertEquals('lorem ipsum', $feedback->content);
        $this->assertEquals($user->email, $feedback->email);
        $this->assertEquals($user->id, $feedback->user_id);
        $this->assertEquals(FeedbackSource::Dashboard->name, $feedback->source);

        $response->assertRedirectToRoute('feedback');
    }

    public function test_it_create_feedback_per_hour(): void
    {
        $this->post(route('feedback.store'), [
            'content' => 'lorem ipsum',
            'email' => 'test@test.fr',
        ]);

        $this->post(route('feedback.store'), [
            'content' => 'lorem ipsum',
            'email' => 'test@test.fr',
        ]);

        $this->assertDatabaseCount('feedback', 1);

        $this->travel(1)->hours();

        $this->post(route('feedback.store'), [
            'content' => 'lorem ipsum',
            'email' => 'test@test.fr',
        ]);

        $this->assertDatabaseCount('feedback', 2);
    }

    public function test_it_create_feedback_with_user_per_hour(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('feedback.store'), [
            'content' => 'lorem ipsum'
        ]);

        $this->actingAs($user)->post(route('feedback.store'), [
            'content' => 'lorem ipsum'
        ]);

        $this->assertDatabaseCount('feedback', 1);

        $this->travel(1)->hours();

        $this->actingAs($user)->post(route('feedback.store'), [
            'content' => 'lorem ipsum'
        ]);

        $this->assertDatabaseCount('feedback', 2);
    }

    public function test_it_can_soft_delete_a_feedback(): void
    {
        $team = Team::factory()->admin()->create();

        $user = User::factory()->team($team->id)->create();

        $feedback = Feedback::factory()->create();

        $response = $this->actingAs($user)->delete(route('feedback.delete'), [
            'feedbackId' => $feedback->id
        ]);

        $this->assertDatabaseCount('feedback', 1);
        $this->assertSoftDeleted($feedback);
        $response->assertRedirectToRoute('admin');
    }

    public function test_it_can_restore_a_feedback(): void
    {
        $team = Team::factory()->admin()->create();

        $user = User::factory()->team($team->id)->create();

        $feedback = Feedback::factory()->deleted()->create();

        $response = $this->actingAs($user)->patch(route('feedback.restore'), [
            'feedbackId' => $feedback->id
        ]);

        $this->assertDatabaseCount('feedback', 1);
        $this->assertNotSoftDeleted($feedback);
        $response->assertRedirectToRoute('admin');
    }

    public function test_it_cant_delete_feedback_if_not_staff_user(): void
    {
        $user = User::factory()->create();

        $feedback = Feedback::factory()->create();

        $response = $this->actingAs($user)->delete(route('feedback.delete'), [
            'feedbackId' => $feedback->id
        ]);

        $this->assertNotSoftDeleted($feedback);
        $response->assertRedirectToRoute('index');
    }

    public function test_it_cant_restore_feedback_if_not_staff_user(): void
    {
        $user = User::factory()->create();

        $feedback = Feedback::factory()->deleted()->create();

        $response = $this->actingAs($user)->patch(route('feedback.restore'), [
            'feedbackId' => $feedback->id
        ]);

        $this->assertSoftDeleted($feedback);
        $response->assertRedirectToRoute('index');
    }
}
