<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Hetbo\Zero\Models\Media;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('migrate');
    Storage::fake('public');
});

test('media model has correct fillable attributes', function () {
    $media = new Media();

    expect($media->getFillable())->toBe([
        'disk',
        'path',
        'filename',
        'mime_type',
        'size',
    ]);
});

test('media model uses correct table', function () {
    $media = new Media();

    expect($media->getTable())->toBe('media');
});

test('media model casts attributes correctly', function () {
    $media = new Media();

    expect($media->getCasts())->toMatchArray([
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ]);
});

test('can create media record', function () {
    $media = Media::create([
        'disk' => 'public',
        'path' => 'media/test-file.txt',
        'filename' => 'test-file.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
    ]);

    expect($media)->toBeInstanceOf(Media::class)
        ->and($media->disk)->toBe('public')
        ->and($media->path)->toBe('media/test-file.txt')
        ->and($media->filename)->toBe('test-file.txt')
        ->and($media->mime_type)->toBe('text/plain')
        ->and($media->size)->toBe(1024);
});

test('url attribute returns correct storage url', function () {
    $media = Media::create([
        'disk' => 'public',
        'path' => 'media/test-file.txt',
        'filename' => 'test-file.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
    ]);

    $expectedUrl = Storage::disk('public')->url('media/test-file.txt');

    expect($media->url)->toBe($expectedUrl);
});

test('full_path attribute returns correct filesystem path', function () {
    $media = Media::create([
        'disk' => 'public',
        'path' => 'media/test-file.txt',
        'filename' => 'test-file.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
    ]);

    $expectedPath = Storage::disk('public')->path('media/test-file.txt');

    expect($media->full_path)->toBe($expectedPath);
});

test('exists method returns true when file exists', function () {
    // Create a real file in storage
    Storage::disk('public')->put('media/existing-file.txt', 'test content');

    $media = Media::create([
        'disk' => 'public',
        'path' => 'media/existing-file.txt',
        'filename' => 'existing-file.txt',
        'mime_type' => 'text/plain',
        'size' => 12,
    ]);

    expect($media->exists())->toBeTrue();
});

test('exists method returns false when file does not exist', function () {
    $media = Media::create([
        'disk' => 'public',
        'path' => 'media/non-existing-file.txt',
        'filename' => 'non-existing-file.txt',
        'mime_type' => 'text/plain',
        'size' => 12,
    ]);

    expect($media->exists())->toBeFalse();
});

test('delete_file method removes file from storage', function () {
    // Create a real file in storage
    Storage::disk('public')->put('media/delete-me.txt', 'content to delete');

    $media = Media::create([
        'disk' => 'public',
        'path' => 'media/delete-me.txt',
        'filename' => 'delete-me.txt',
        'mime_type' => 'text/plain',
        'size' => 17,
    ]);

    // Confirm file exists
    Storage::disk('public')->assertExists('media/delete-me.txt');

    // Delete file
    $result = $media->deleteFile();

    expect($result)->toBeTrue();
    Storage::disk('public')->assertMissing('media/delete-me.txt');
});

test('human_size attribute formats bytes correctly', function () {
    $testCases = [
        ['size' => 0, 'expected' => '0 B'],
        ['size' => 500, 'expected' => '500 B'],
        ['size' => 1024, 'expected' => '1 KB'],
        ['size' => 1536, 'expected' => '1.5 KB'],
        ['size' => 1048576, 'expected' => '1 MB'],
        ['size' => 1073741824, 'expected' => '1 GB'],
        ['size' => 2684354560, 'expected' => '2.5 GB'],
    ];

    foreach ($testCases as $case) {
        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/size-test.txt',
            'filename' => 'size-test.txt',
            'mime_type' => 'text/plain',
            'size' => $case['size'],
        ]);

        expect($media->human_size)->toBe($case['expected']);

        // Clean up for next iteration
        $media->delete();
    }
});

test('can query media by mime type', function () {
    Media::create([
        'disk' => 'public',
        'path' => 'media/image.jpg',
        'filename' => 'image.jpg',
        'mime_type' => 'image/jpeg',
        'size' => 1024,
    ]);

    Media::create([
        'disk' => 'public',
        'path' => 'media/document.pdf',
        'filename' => 'document.pdf',
        'mime_type' => 'application/pdf',
        'size' => 2048,
    ]);

    $images = Media::where('mime_type', 'like', 'image/%')->get();
    $documents = Media::where('mime_type', 'application/pdf')->get();

    expect($images)->toHaveCount(1)
        ->and($documents)->toHaveCount(1)
        ->and($images->first()->filename)->toBe('image.jpg')
        ->and($documents->first()->filename)->toBe('document.pdf');
});

test('media records have timestamps', function () {
    $media = Media::create([
        'disk' => 'public',
        'path' => 'media/timestamp-test.txt',
        'filename' => 'timestamp-test.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
    ]);

    expect($media->created_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($media->updated_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($media->created_at)->not->toBeNull()
        ->and($media->updated_at)->not->toBeNull();
});