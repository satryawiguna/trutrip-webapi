<?php

namespace Tests\Unit;

use App\Models\Contact;
use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_use_the_correct_driver()
    {
        $expectDrivers = ['smtp'];

        $this->seed(RoleSeeder::class);

        Contact::factory(1)->create();

        $user = (new User())->with('contact')->first();

        $notification = new WelcomeEmailNotification($user);

        $this->assertEquals($expectDrivers, $notification->via(new User));
    }

    /**
     * @test
     */
    public function it_send_the_correct_message()
    {
        $this->seed(RoleSeeder::class);

        Contact::factory(1)->create();

        $user = (new User())->with('contact')->first();
        $notification = new WelcomeEmailNotification($user);

        $expectSubject = "Travel Subscription";
        $expectGreeting = "Hello, " . $user->contact->full_name;
        $expectIntrolines = ["Welcome to Trutrip."];
        $expectOutrolines = ["Thank you for using our application!"];
        $expectActionTest = "Explore";
        $expectActionUrl = url('/');

        $message = $notification->toMail($user);

        $this->assertEquals($expectSubject, $message->subject);
        $this->assertEquals($expectGreeting, $message->greeting);
        $this->assertEquals($expectIntrolines, $message->introLines);
        $this->assertEquals($expectOutrolines, $message->outroLines);
        $this->assertEquals($expectActionTest, $message->actionText);
        $this->assertEquals($expectActionUrl, $message->actionUrl);

    }
}
