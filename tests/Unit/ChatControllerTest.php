<?php

namespace Tests\Unit;

use App\Http\Controllers\ChatController;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use App\Http\Interfaces\Services\ILeftMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatControllerTest extends TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testChatController()
    {
        $this->loginWithFakeUser();

        $leftMenu = \Mockery::mock(ILeftMenu::class);
        $leftMenu->shouldReceive('getLinks')->once()->andReturn([
            'profile' => '/profile/id1'
        ]);
        App::instance(ILeftMenu::class, $leftMenu);

        $conv = \Mockery::mock(Conversation::class);
        $conv->shouldReceive('checkIfExists')->andReturn();
        App::instance(Conversation::class, $conv);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('checkIfExists')->once()->andReturn(true);
        App::instance(User::class, $user);

        $response = $this->get('/msg/write1');

        $response->assertStatus(200);
    }

    public function loginWithFakeUser()
    {
        $user = new User([
            'id' => 1,
            'surname' => 'Miller',
            'name' => 'Jake',
            'email' => 'jake@gmail.com',
            'password' => 'test123',
            'gender' => 'male',
            'birth' => '2000-01-01 00:00:00',
            'avatar' => 'http://any.jpg',
            'active' => 1
        ]);

        $this->be($user);
    }
}
