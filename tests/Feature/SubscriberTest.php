<?php

namespace Tests\Feature;

use App\Enums\FieldType;
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
    public function testIndex()
    {
        factory(Subscriber::class, 50)->create();

        $response = $this->get('/api/subscriber');

        $response->assertStatus(200)->assertJsonCount(10, 'data');

        $this->assertEquals(50, Arr::get($response->json('data'), '0.id'));

    }

    /**
     * @test
     */
    public function testIndexSorted()
    {
        factory(Subscriber::class, 50)->create();

        $response = $this->get('/api/subscriber?sorting={"by":"id","desc":false}');

        $response->assertStatus(200)->assertJsonCount(10, 'data');

        $this->assertEquals(1, Arr::get($response->json('data'), '0.id'));

    }

    /**
     * @test
     */
    public function testIndexFiltered()
    {
        factory(Subscriber::class, 50)->create();

        $response = $this->get('/api/subscriber?state[]=ACTIVE');

        $response->assertStatus(200);

        foreach ($response->json('data') as $subscriber) {
            $this->assertEquals(SubscriberState::ACTIVE(), $subscriber['state']);
        }

    }

    /**
     * @test
     */
    public function testPostSubscriber()
    {
        $response = $this->post('/api/subscriber', [
            'name' => 'Juan Angarita',
            'email' => 'juan@mailerlite.com'
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
        $field->type = FieldType::STRING();
        $field->save();

        $subscriber->fields()->attach($field->id, ['value' => 'test']);

        $response = $this->patch('/api/subscriber/' . $subscriber->getKey(), [
            'name' => 'Juan Angarita',
            'email' => 'juan@mailerlite.com',
            'fields' => collect([$field->id => 'test'])
        ]);

        $response->assertStatus(200);

        $field->value = 'test';

        $response->assertJsonFragment([
            'name' => 'Juan Angarita',
            'email' => 'juan@mailerlite.com'
        ]);

        $this->assertTrue(
            collect($response->decodeResponseJson('data.fields'))
                ->pluck('value')->contains('test')
        );

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
