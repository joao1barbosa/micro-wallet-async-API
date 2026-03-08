<?php

namespace Tests;

use App\Models\Transfer;
use Laravel\Lumen\Testing\DatabaseMigrations;

class TransferTest extends TestCase
{
    use DatabaseMigrations;
    public function test_create_transfer_returns_202()
    {
        $payload = [
            'sender_id'   => 1,
            'receiver_id' => 2,
            'amount'      => 150.00,
        ];

        $this->post('/api/v1/transfers', $payload);

        $this->assertResponseStatus(202);
        $this->seeJsonStructure(['message']);
    }

    public function test_create_transfer_requires_sender_id()
    {
        $payload = [
            'receiver_id' => 2,
            'amount'      => 150.00,
        ];

        $this->post('/api/v1/transfers', $payload);

        $this->assertResponseStatus(422);
    }

    public function test_create_transfer_requires_receiver_id()
    {
        $payload = [
            'sender_id' => 1,
            'amount'    => 150.00,
        ];

        $this->post('/api/v1/transfers', $payload);

        $this->assertResponseStatus(422);
    }

    public function test_create_transfer_requires_amount()
    {
        $payload = [
            'sender_id'   => 1,
            'receiver_id' => 2,
        ];

        $this->post('/api/v1/transfers', $payload);

        $this->assertResponseStatus(422);
    }

    public function test_create_transfer_persists_to_database()
    {
        $payload = [
            'sender_id'   => 1,
            'receiver_id' => 2,
            'amount'      => 150.00,
        ];

        $this->post('/api/v1/transfers', $payload);

        $this->assertResponseStatus(202);
        $this->seeInDatabase('transfers', [
            'sender_id'   => 1,
            'receiver_id' => 2,
            'amount'      => 15000,
            'status'      => 'pending',
        ]);
    }

    public function test_show_transfer_returns_correct_data()
    {
        $transfer = Transfer::create([
            'sender_id'   => 1,
            'receiver_id' => 2,
            'amount'      => 15000,
            'status'      => 'pending',
        ]);

        $this->get("/api/v1/transfers/{$transfer->id}");

        $this->assertResponseOk();
        $this->seeJson([
            'id'          => $transfer->id,
            'sender_id'   => 1,
            'receiver_id' => 2,
            'amount'      => 15000,
            'status'      => 'pending',
        ]);
    }

    public function test_show_transfer_returns_404_when_not_found()
    {
        $this->get('/api/v1/transfers/non-existent-id');

        $this->assertResponseStatus(404);
    }
}
