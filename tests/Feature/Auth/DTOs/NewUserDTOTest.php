<?php

namespace Auth\DTOs;

use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewUserDTOTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_instance_created_from_form_request()
    {
        $test_data = [
            'name' => 'Name',
            'email' => 'email@email.com',
            'password' => 'password',
        ];

        $dto = NewUserDTO::fromRequest(new SignUpFormRequest($test_data));

        $this->assertInstanceOf(NewUserDTO::class, $dto);
    }
}
