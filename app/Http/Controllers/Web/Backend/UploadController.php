<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function chunk(Request $request)
    {
        $file = $request->file('file');

        $uuid        = $request->input('dzuuid');
        $chunkIndex  = (int) $request->input('dzchunkindex');
        $totalChunks = (int) $request->input('dztotalchunkcount');

        $fileName = $request->input('dzfilename')
            ?: $file->getClientOriginalName();

        // sanitize filename
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $baseName  = pathinfo($fileName, PATHINFO_FILENAME);
        $baseName  = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName);
        $fileName  = $baseName . '.' . $extension;

        $chunkDir = storage_path("app/chunks/{$uuid}");
        if (!is_dir($chunkDir)) {
            mkdir($chunkDir, 0777, true);
        }

        $file->move($chunkDir, $chunkIndex);

        if ($chunkIndex + 1 < $totalChunks) {
            return response()->json(['chunk_received' => true]);
        }

        $finalDir = storage_path('app/public/uploads');
        if (!is_dir($finalDir)) {
            mkdir($finalDir, 0777, true);
        }

        $finalPath = $finalDir . '/' . uniqid() . '_' . $fileName;

        $out = fopen($finalPath, 'wb');

        for ($i = 0; $i < $totalChunks; $i++) {
            fwrite($out, file_get_contents($chunkDir . '/' . $i));
            unlink($chunkDir . '/' . $i);
        }

        fclose($out);
        rmdir($chunkDir);

        return response()->json([
            'path' => 'storage/uploads/' . basename($finalPath)
        ]);
    }
}
