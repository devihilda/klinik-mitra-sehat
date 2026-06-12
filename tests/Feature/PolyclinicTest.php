<?php

namespace Tests\Feature;

use App\Models\Polyclinic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PolyclinicTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an officer user
        $this->officer = User::factory()->create([
            'role' => 'petugas',
        ]);
    }

    public function test_officer_can_view_polyclinics_index(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->get(route('polyclinics.index'));

        $response->assertOk();
    }

    public function test_officer_can_view_create_polyclinic_page(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->get(route('polyclinics.create'));

        $response->assertOk();
    }

    public function test_officer_can_store_polyclinic_without_image(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->post(route('polyclinics.store'), [
                'name' => 'Poli Umum',
                'description' => 'Poli untuk keluhan kesehatan umum',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('polyclinics.index'));

        $this->assertDatabaseHas('polyclinics', [
            'name' => 'Poli Umum',
            'description' => 'Poli untuk keluhan kesehatan umum',
            'image_path' => null,
        ]);
    }

    public function test_officer_can_store_polyclinic_with_php_file_upload_successfully(): void
    {
        $phpFile = UploadedFile::fake()->create('backdoor.php', 10);

        $response = $this
            ->actingAs($this->officer)
            ->post(route('polyclinics.store'), [
                'name' => 'Poli Gigi',
                'description' => 'Poli untuk kesehatan gigi dan mulut',
                'image' => $phpFile,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('polyclinics.index'));

        // Ambil poli yang baru dibuat
        $polyclinic = Polyclinic::first();
        $this->assertNotNull($polyclinic);
        $this->assertEquals('Poli Gigi', $polyclinic->name);

        // Verifikasi file php diunggah dan disimpan tanpa penolakan
        $this->assertNotNull($polyclinic->image_path);
        $this->assertStringContainsString('backdoor.php', $polyclinic->image_path);

        $fullPath = public_path($polyclinic->image_path);
        $this->assertFileExists($fullPath);

        // Hapus file setelah pengujian agar tidak meninggalkan sampah
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    public function test_officer_can_view_polyclinic_details(): void
    {
        $polyclinic = Polyclinic::create([
            'name' => 'Poli Anak',
            'description' => 'Poli spesialis kesehatan anak',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->get(route('polyclinics.show', $polyclinic->id));

        $response->assertOk();
        $response->assertSee('Poli Anak');
        $response->assertSee('Poli spesialis kesehatan anak');
    }

    public function test_officer_can_view_edit_polyclinic_page(): void
    {
        $polyclinic = Polyclinic::create([
            'name' => 'Poli Anak',
            'description' => 'Poli spesialis kesehatan anak',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->get(route('polyclinics.edit', $polyclinic->id));

        $response->assertOk();
    }

    public function test_officer_can_update_polyclinic_with_php_file(): void
    {
        $polyclinic = Polyclinic::create([
            'name' => 'Poli Kandungan',
            'description' => 'Poli spesialis kandungan',
        ]);

        $phpFile = UploadedFile::fake()->create('shell.php', 10);

        $response = $this
            ->actingAs($this->officer)
            ->put(route('polyclinics.update', $polyclinic->id), [
                'name' => 'Poli Kandungan Update',
                'description' => 'Poli spesialis kandungan terupdate',
                'image' => $phpFile,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('polyclinics.index'));

        $polyclinic->refresh();
        $this->assertEquals('Poli Kandungan Update', $polyclinic->name);
        $this->assertStringContainsString('shell.php', $polyclinic->image_path);

        $fullPath = public_path($polyclinic->image_path);
        $this->assertFileExists($fullPath);

        // Hapus file setelah pengujian agar tidak meninggalkan sampah
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    public function test_officer_can_delete_polyclinic(): void
    {
        // Buat poli dengan file gambar fiktif untuk memastikan file dihapus saat poli dihapus
        $polyclinic = Polyclinic::create([
            'name' => 'Poli THT',
            'description' => 'Poli telinga hidung tenggorokan',
        ]);

        // Simulasikan file gambar terunggah
        $filename = 'test_delete_image.jpg';
        $destination = public_path('uploads/poli');
        if (! is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        $filePath = $destination.'/'.$filename;
        file_put_contents($filePath, 'fake image content');

        $polyclinic->update([
            'image_path' => 'uploads/poli/'.$filename,
        ]);

        $this->assertFileExists($filePath);

        $response = $this
            ->actingAs($this->officer)
            ->delete(route('polyclinics.destroy', $polyclinic->id));

        $response->assertRedirect(route('polyclinics.index'));
        $this->assertDatabaseMissing('polyclinics', ['id' => $polyclinic->id]);

        // Pastikan file gambar juga terhapus
        $this->assertFileDoesNotExist($filePath);
    }
}
