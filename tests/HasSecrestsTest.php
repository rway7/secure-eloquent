<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;
use rway7\SecureEloquent\HasSecrets;

class HasSecretsTest extends TestCase
{
    public function test_can_secure_a_model()
    {
        $model = new Post([
            'body' => 'Body',
        ]);

        $model->secure('secret');

        $this->assertNotEquals('Body', $model->body);
        $this->assertTrue($model->is_secured);
        $this->assertTrue($model->secured());
    }

    public function test_can_unsecure_a_model() {
        $secured = (new Post([
            'body' => 'Body',
        ]))->secure('secret');

        $unsecured = $secured->unsecure('secret');

        $this->assertEquals('Body', $unsecured->body);
        $this->assertFalse($unsecured->is_secured);
        $this->assertFalse($unsecured->secured());
    }
}

class Post extends Model
{
    use HasSecrets;

    protected $guarded = [];

    protected $secrets = [
        'title', 'body',
    ];
}
