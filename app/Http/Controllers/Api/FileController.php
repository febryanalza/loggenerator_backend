<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    /**
     * Get logbook image by filename.
     *
     * Since images are stored on Supabase Storage (public bucket), this
     * endpoint redirects to the Supabase public URL for direct browser access.
     *
     * @param  string  $filename
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogbookImage($filename)
    {
        try {
            $filename = basename($filename); // Prevent directory traversal

            // Generate the public Supabase URL and redirect
            $url = Storage::disk('s3_logbook')->url($filename);

            return redirect()->away($url, 301);
        } catch (\Exception $e) {
            Log::error('FileController@getLogbookImage error: ' . $e->getMessage());
            abort(500, 'Error retrieving image.');
        }
    }

    /**
     * Get avatar image by filename.
     *
     * Since images are stored on Supabase Storage (public bucket), this
     * endpoint redirects to the Supabase public URL for direct browser access.
     *
     * @param  string  $filename
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAvatarImage($filename)
    {
        try {
            $filename = basename($filename); // Prevent directory traversal

            // Generate the public Supabase URL and redirect
            $url = Storage::disk('s3_avatars')->url($filename);

            return redirect()->away($url, 301);
        } catch (\Exception $e) {
            Log::error('FileController@getAvatarImage error: ' . $e->getMessage());
            abort(500, 'Error retrieving avatar image.');
        }
    }

    /**
     * Upload a single image to Supabase Storage (via S3-compatible API).
     *
     * Accepts multipart/form-data with an 'image' field.
     * Returns the Supabase public URL of the uploaded file.
     *
     * NOTE: File visibility (public/private) is controlled by the bucket's
     * Row Level Security (RLS) policy in Supabase, not by the upload call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
            ]);

            $image    = $request->file('image');
            $ext      = strtolower($image->getClientOriginalExtension());
            $filename = 'logbook_' . time() . '_' . uniqid() . '.' . $ext;

            // Upload to Supabase Storage via s3_logbook disk (root: logbook_images/)
            // Note: Do NOT pass 'visibility' option — Supabase does not support per-object ACLs.
            //       Public access is governed by the bucket's RLS policy.
            $stored = Storage::disk('s3_logbook')->putFileAs('', $image, $filename);

            if ($stored === false) {
                throw new \RuntimeException('Storage::putFileAs returned false. Check Supabase credentials and bucket policy.');
            }

            // Generate the public Supabase URL
            $url = Storage::disk('s3_logbook')->url($filename);

            Log::info('Image uploaded to Supabase', ['path' => 'logbook_images/' . $filename, 'url' => $url]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data'    => [
                    'filename' => $filename,
                    'url'      => $url,
                    'storage'  => 'supabase',
                    'path'     => 'logbook_images/' . $filename,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('FileController@uploadImage error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage(),
            ], 500);
        }
    }
}
