<?php

namespace Hetbo\Zero\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Hetbo\Zero\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of all media files
     */
    public function index(): View
    {
        $mediaFiles = Media::latest()->get();

        return view('zero::zero.index', compact('mediaFiles'));
    }

    /**
     * Store a newly uploaded file
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');

        // Store the file in storage/app/public
        $path = $file->store('media', 'public');

        // Create media record
        $media = Media::create([
            'disk' => 'public',
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'media' => $media,
                'message' => 'File uploaded successfully'
            ]);
        }

        return redirect()->route('media.index')
            ->with('success', 'File uploaded successfully');
    }

    /**
     * Remove the specified media file
     */
    public function destroy($id): RedirectResponse|JsonResponse
    {
        $media = Media::findOrFail($id);

        // Delete file from storage (only if path exists)
        if ($media->path) {
            $media->deleteFile();
        }

        // Delete database record
        $media->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        }

        return redirect()->route('media.index')
            ->with('success', 'File deleted successfully');
    }
}