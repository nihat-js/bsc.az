<?php


namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
  public static function uploadBase64Image($base64Image, $uploadPath)
  {
    if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
      throw new \Exception('Invalid base64 image data');
    }

    $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
    $mimeType = $matches[1];

    if (!in_array($mimeType, ['jpeg', 'jpg', 'png'])) {
      throw new \Exception('Invalid image type. Only jpeg, png, and jpg are allowed.');
    }

    $imageData = base64_decode($imageData);
    if ($imageData === false) {
      throw new \Exception('Invalid base64 image data');
    }

    $fileName = time() . '_' . uniqid() . '.' . $mimeType;
    $path = Storage::disk("public")->put($uploadPath . '/' . $fileName, $imageData);

    if (!$path) {
      throw new \Exception('Failed to save image');
    }

    return $fileName;
  }
}
