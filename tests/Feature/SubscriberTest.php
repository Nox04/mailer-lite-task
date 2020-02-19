<?php

namespace Tests\Feature;

use App\Enums\SubscriberState;
use App\Models\Field;
use App\Models\Subscriber;
use Arr;
use function factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class SubscriberTest
 * @package Tests\Feature
 */
class SubscriberTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @test
     */
    public function testPostSubscriber()
    {
        $response = $this->post('/api/subscriber', [
            'name' => 'John Doe',
            'email' => 'johndoe@mailerlite.com'
        ]);
        $response->assertStatus(201);

        $response->assertJsonFragment([
            'state' => SubscriberState::UNCONFIRMED(),
        ]);

        $response->assertJsonStructure(['data' => [
            'name',
            'email',
            'fields',
        ]]);
    }

    /**
     * @test
     */
    public function testPatchSubscriber()
    {
        /** @var Subscriber $subscriber */
        $subscriber = factory(Subscriber::class, 1)->create()->first();

        /** @var Field $field */
        $field = factory(Field::class, 1)->create()->first();

        $subscriber->fields()->attach($field->id, ['value' => 'test']);

        $response = $this->patch('/api/subscriber/' . $subscriber->getKey(), [
            'name' => 'John Doe',
            'email' => 'johndoe@mailerlite.com',
            'fields' => []
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => 'John Doe',
            'email' => 'johndoe@mailerlite.com',
            'fields' => []
        ]);

        $response->assertJsonStructure(['data' => [
            'name',
            'email',
            'fields',
        ]]);
    }

    /**
     * @test
     */
    public function testDeleteSubscriber()
    {
        /** @var Subscriber $subscriber */
        $subscriber = factory(Subscriber::class, 1)->create([
            'name' => 'Test'
        ])->first();

        /** @var Field $field */
        $field = factory(Field::class, 1)->create()->first();

        $subscriber->fields()->attach($field->id, ['value' => 'test']);

        $response = $this->delete('/api/subscriber/' . $subscriber->getKey());

        $response->assertStatus(200);

        $this->assertDatabaseMissing('subscribers', [
            'name' => 'Test'
        ]);
    }
}