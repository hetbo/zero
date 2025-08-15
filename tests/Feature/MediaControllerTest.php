<?php

use Hetbo\Zero\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('migrate');
    Storage::fake('public');
});

test('can view media index page', function () {
    $response = $this->get(route('media.index'));

    $response->assertStatus(200);
    $response->assertViewIs('media-manager::index');
    $response->assertSeeText('Media Manager');
    $response->assertSeeText('Upload New File');
});

test('can upload a file', function () {
    $file = createTestFile('document.pdf', 'fake pdf content');

    $response = $this->post(route('media.store'), [
        'file' => $file
    ]);

    $response->assertRedirect(route('media.index'));
    $response->assertSessionHas('success', 'File uploaded successfully');

    // Check database
    $this->assertDatabaseHas('media', [
        'filename' => 'document.pdf',
        'mime_type' => 'application/pdf',
        'disk' => 'public',
    ]);

    // Check file was stored
    $media = Media::first();
    Storage::disk('public')->assertExists($media->path);
});

test('can upload an image', function () {
    $image = createTestImage('photo.jpg');

    $response = $this->post(route('media.store'), [
        'file' => $image
    ]);

    $response->assertRedirect(route('media.index'));

    $this->assertDatabaseHas('media', [
        'filename' => 'photo.jpg',
        'mime_type' => 'image/jpeg',
    ]);
});

test('upload validates file is required', function () {
    $response = $this->post(route('media.store'), []);

    $response->assertSessionHasErrors('file');
    $this->assertDatabaseCount('media', 0);
});

test('upload validates file size limit', function () {
    // Create a file larger than 10MB (10240KB)
    $largeFile = UploadedFile::fake()->create('large.txt', 11000); // 11MB

    $response = $this->post(route('media.store'), [
        'file' => $largeFile
    ]);

    $response->assertSessionHasErrors('file');
    $this->assertDatabaseCount('media', 0);
});

test('can delete a media file', function () {
    // Create a file first
    $file = createTestFile('delete-me.txt');
    $this->post(route('media.store'), ['file' => $file]);

    $media = Media::first();
    $filePath = $media->path;

    // Confirm file exists
    Storage::disk('public')->assertExists($filePath);

    // Delete the media using ID
    $response = $this->delete(route('media.destroy', $media->id));

    $response->assertRedirect(route('media.index'));
    $response->assertSessionHas('success', 'File deleted successfully');

    // Check database
    $this->assertDatabaseCount('media', 0);

    // Check file was deleted from storage
    Storage::disk('public')->assertMissing($filePath);
});

test('index page shows uploaded files', function () {
    // Upload some files
    $this->post(route('media.store'), ['file' => createTestFile('doc1.txt')]);
    $this->post(route('media.store'), ['file' => createTestImage('image1.jpg')]);

    $response = $this->get(route('media.index'));

    $response->assertSeeText('doc1.txt');
    $response->assertSeeText('image1.jpg');
    $response->assertSeeText('Media Files (2 files)');
});

test('index page shows no files message when empty', function () {
    $response = $this->get(route('media.index'));

    $response->assertSeeText('No media files uploaded yet');
    $response->assertSeeText('Media Files (0 files)');
});

test('can handle json upload request', function () {
    $file = createTestFile('api-upload.txt');

    $response = $this->postJson(route('media.store'), [
        'file' => $file
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'media' => ['id', 'filename', 'mime_type', 'size'],
        'message'
    ]);

    $this->assertDatabaseHas('media', [
        'filename' => 'api-upload.txt'
    ]);
});

test('can handle json delete request', function () {
    // Create a file first
    $this->post(route('media.store'), ['file' => createTestFile('api-delete.txt')]);
    $media = Media::first();

    $response = $this->deleteJson(route('media.destroy', $media->id));

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'File deleted successfully'
    ]);

    $this->assertDatabaseCount('media', 0);
});