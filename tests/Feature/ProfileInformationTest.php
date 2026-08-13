<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_profile_information_is_available(): void
    {
        $this->actingAs($user = User::factory()->create());

        $component = Livewire::test(UpdateProfileInformationForm::class);

        $this->assertEquals($user->name, $component->state['name']);
        $this->assertEquals($user->email, $component->state['email']);
    }

    public function test_profile_information_can_be_updated(): void
    {
        $this->actingAs($user = User::factory()->create());

        Livewire::test(UpdateProfileInformationForm::class)
            ->set('state', ['name' => 'Test Name', 'email' => 'test@example.com'])
            ->call('updateProfileInformation');

        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
    }

    public function test_profile_photo_can_be_uploaded(): void
    {
        Storage::fake('public');
        $this->actingAs($user = User::factory()->create());
        $photo = UploadedFile::fake()->image('avatar.jpg', 300, 300);

        Livewire::test(UpdateProfileInformationForm::class)
            ->set('photo', $photo)
            ->set('state', ['name' => $user->name, 'email' => $user->email])
            ->call('updateProfileInformation');

        $profilePhotoPath = $user->fresh()->profile_photo_path;

        $this->assertNotNull($profilePhotoPath);
        Storage::disk('public')->assertExists($profilePhotoPath);
    }
}
