<?php

namespace App\Services\Medicine;

use App\Models\MedicineImage;
use App\Models\Medicine;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class MedicineImageService
{
    public function getMedicineImages(int $medicineId)
    {
        return MedicineImage::where('medicine_id', $medicineId)
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    public function uploadImages(int $medicineId, array $files)
    {
        $medicine = Medicine::findOrFail($medicineId);
        $uploadedImages = [];
        
        $currentMaxSort = MedicineImage::where('medicine_id', $medicineId)->max('sort_order') ?? 0;
        $hasPrimary = MedicineImage::where('medicine_id', $medicineId)->where('is_primary', true)->exists();

        foreach ($files as $index => $file) {
            $path = $file->store('medicines/images', 'public');
            
            $isPrimary = false;
            if (!$hasPrimary && $index === 0) {
                $isPrimary = true;
                $hasPrimary = true;
            }

            $uploadedImages[] = MedicineImage::create([
                'medicine_id' => $medicineId,
                'image' => $path,
                'image_name' => $file->getClientOriginalName(),
                'sort_order' => $currentMaxSort + $index + 1,
                'is_primary' => $isPrimary,
            ]);
        }
        
        return $uploadedImages;
    }

    public function getImage(int $imageId)
    {
        return MedicineImage::findOrFail($imageId);
    }

    public function replaceImage(int $imageId, UploadedFile $file)
    {
        $imageRecord = MedicineImage::findOrFail($imageId);
        
        if (Storage::disk('public')->exists($imageRecord->image)) {
            Storage::disk('public')->delete($imageRecord->image);
        }
        
        $path = $file->store('medicines/images', 'public');
        
        $imageRecord->update([
            'image' => $path,
            'image_name' => $file->getClientOriginalName(),
        ]);
        
        return $imageRecord;
    }

    public function setPrimary(int $imageId)
    {
        $imageRecord = MedicineImage::findOrFail($imageId);
        
        MedicineImage::where('medicine_id', $imageRecord->medicine_id)
            ->where('id', '!=', $imageId)
            ->update(['is_primary' => false]);
            
        $imageRecord->update(['is_primary' => true]);
        
        return $imageRecord;
    }

    public function reorder(array $orders)
    {
        foreach ($orders as $order) {
            MedicineImage::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }
        return true;
    }

    public function deleteImage(int $imageId)
    {
        $imageRecord = MedicineImage::findOrFail($imageId);
        
        if (Storage::disk('public')->exists($imageRecord->image)) {
            Storage::disk('public')->delete($imageRecord->image);
        }
        
        $wasPrimary = $imageRecord->is_primary;
        $medicineId = $imageRecord->medicine_id;
        
        $imageRecord->delete();
        
        if ($wasPrimary) {
            $nextImage = MedicineImage::where('medicine_id', $medicineId)->orderBy('sort_order')->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }
        
        return true;
    }
}
