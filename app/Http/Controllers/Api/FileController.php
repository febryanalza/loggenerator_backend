<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class FileController extends Controller
{
    /**
     * Get logbook image by filename.
     *
     * Since images are now stored on Amazon S3 (public bucket),
     * this endpoint redirects to the S3 URL directly for better performance.
     * The S3 URL is cached by the browser via the redirect response.
     *
     * @param string $filename
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogbookImage($filename)
    {
        try {
            // Sanitize filename to prevent directory traversal
            $filename = basename($filename);

            // Check if file exists on S3
            if (!Storage::disk('s3_logbook')->exists($filename)) {
                abort(404, 'Image not found');
            }

            // Redirect to the public S3 URL (permanent redirect for caching)
            $s3Url = Storage::disk('s3_logbook')->url($filename);

            return redirect()->away($s3Url, 301);
        } catch (\Exception $e) {
            abort(500, 'Error retrieving image: ' . $e->getMessage());
        }
    }

    /**
     * Get avatar image by filename.
     *
     * Since images are now stored on Amazon S3 (public bucket),
     * this endpoint redirects to the S3 URL directly for better performance.
     *
     * @param string $filename
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAvatarImage($filename)
    {
        try {
            // Sanitize filename to prevent directory traversal
            $filename = basename($filename);

            // Check if file exists on S3
            if (!Storage::disk('s3_avatars')->exists($filename)) {
                abort(404, 'Avatar image not found');
            }

            // Redirect to the public S3 URL (permanent redirect for caching)
            $s3Url = Storage::disk('s3_avatars')->url($filename);

            return redirect()->away($s3Url, 301);
        } catch (\Exception $e) {
            abort(500, 'Error retrieving avatar image: ' . $e->getMessage());
        }
    }

    /**
     * Upload a single image file to Amazon S3.
     *
     * Accepts multipart/form-data with an 'image' field.
     * Returns the public S3 URL of the uploaded file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // Max 5MB
            ]);

            $image    = $request->file('image');
            $filename = 'logbook_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the image on the S3 logbook disk (root: logbook_images/)
            // The file is stored with public visibility so it can be accessed directly via URL
            Storage::disk('s3_logbook')->putFileAs('', $image, $filename, [
                'visibility' => 'public',
                'ContentType' => $image->getMimeType(),
            ]);

            // Generate the public S3 URL
            $url = Storage::disk('s3_logbook')->url($filename);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data'    => [
                    'filename' => $filename,
                    'url'      => $url,
                    'storage'  => 's3',
                    'path'     => 'logbook_images/' . $filename,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ], 500);
        }
    }
}
