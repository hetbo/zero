<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Media Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .upload-section { background: #f5f5f5; padding: 20px; border-radius: 5px; margin-bottom: 30px; }
        .file-input { margin: 10px 0; }
        .btn { padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer; }
        .btn:hover { background: #005a87; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .media-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .media-table th, .media-table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        .media-table th { background: #f8f9fa; font-weight: bold; }
        .media-table tr:hover { background: #f8f9fa; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .filename { font-weight: 500; }
        .file-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 3px; }
        .no-media { text-align: center; color: #6c757d; font-style: italic; padding: 40px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Media Manager</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Upload Section -->
    <div class="upload-section">
        <h2>Upload New File</h2>
        <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="file-input">
                <input type="file" name="file" required>
            </div>
            <button type="submit" class="btn">Upload File</button>
        </form>
        @if(session('error'))
            <div style="color: red; margin-top: 10px;">{{ session('error') }}</div>
        @endif
    </div>

    <!-- Media Files Table -->
    <div class="media-section">
        <h2>Media Files ({{ $mediaFiles->count() }} files)</h2>

        @if($mediaFiles->count() > 0)
            <table class="media-table">
                <thead>
                <tr>
                    <th>Preview</th>
                    <th>Filename</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Uploaded</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($mediaFiles as $media)
                    <tr>
                        <td>
                            @if(str_starts_with($media->mime_type, 'image/'))
                                <img src="{{ $media->url }}" alt="{{ $media->filename }}" class="file-preview">
                            @else
                                <div class="file-preview" style="background: #e9ecef; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                    {{ strtoupper(pathinfo($media->filename, PATHINFO_EXTENSION)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="filename">{{ $media->filename }}</div>
                            <small style="color: #6c757d;">{{ basename($media->path) }}</small>
                        </td>
                        <td>{{ $media->mime_type }}</td>
                        <td>{{ $media->human_size }}</td>
                        <td>{{ $media->created_at->format('M j, Y \a\t g:i A') }}</td>
                        <td>
                            <form action="{{ route('media.destroy', $media->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this file?')">>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="no-media">
                <p>No media files uploaded yet.</p>
                <p>Use the upload form above to add your first file.</p>
            </div>
        @endif
    </div>
</div>
</body>
</html>