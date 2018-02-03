<?php
namespace Tests\Feature;

use App\Http\Controllers\NewsController;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class NewsControllerTest extends TestCase
{
    public function testIndex()
    {
        /*$news = new NewsController();
        $news = $news->index();

        $this->assertTrue($news);*/

        /*$user = factory(User::class)->make();
        $response = $this->actingAs($user)
            ->get('/news');

        $response->assertSuccessful();*/
    }
}