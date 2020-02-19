<?php

namespace Tests\Unit;

use App\Enums\SubscriberState;
use Tests\TestCase;

/**
 * Class SubscriberTest
 * @package Tests\Unit
 */
class SubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function testHasStates()
    {
        $this->assertEquals([
            SubscriberState::ACTIVE(),
            SubscriberState::BOUNCED(),
            SubscriberState::JUNK(),
            SubscriberState::UNCONFIRMED(),
            SubscriberState::UNSUBSCRIBED(),
        ], SubscriberState::getNames());
    }
}
