<?php

namespace App\Services\Cabinet;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PlayerAssetsService
{
    private array $validSkinSizes = [32, 64, 128, 256, 512, 1024];
    private array $validCapeSizes = [16, 32, 64, 128, 512];
    private array $additionalCapeSizes = ['width' => 22, 'height' => 17];

    public function upload($skin, $type, $userName): void
    {
        $skinSize = $this->getSkinSize($skin);
        if (!$this->isValidSkinSize($skinSize['width'], $skinSize['height'], $type)) {
            throw new \InvalidArgumentException("Invalid $type size");
        }
        $skin->storeAs("public/player/$type/$userName.png");
    }

    public function remove($type, $filePath): void
    {
        $fileType = ($type === 'skin') ? 'Skin' : 'Cape';

        if (File::exists($filePath)) {
            File::delete($filePath);
        } else {
            throw new \Exception("File $fileType not found");
        }
    }

    public function getSkinAndCapePaths($userName): array
    {
        $defaultSkinPath = 'storage/player/defaultSkin.png';
        $capePath = '';
        $userSkinPath = 'storage/player/skin/' . $userName . '.png';
        $userCapePath = 'storage/player/cape/' . $userName . '.png';

        if (!File::exists($userSkinPath)) {
            $userSkinPath = $defaultSkinPath;
        }
        if (File::exists($userCapePath)) {
            $capePath = $userCapePath;
        }
        return ['skinPath' => $userSkinPath, 'capePath' => $capePath, 'defaultSkinPath' => $defaultSkinPath];
    }

    public function getAllValidSkinSizes($type): string
    {
        $result = [];
        if ($type === 'skin') {
            foreach ($this->validSkinSizes as $size) {
                $result[] = $size . '/' . ($size / 2);
                $result[] = $size . '/' . $size;
            }
        }
        if ($type === 'cape') {
            $result[] = $this->additionalCapeSizes['width'] . '/' . $this->additionalCapeSizes['height'];
            foreach ($this->validCapeSizes as $size) {
                $result[] = $size . '/' . ($size / 2);
            }
        }
        return implode(', ', $result);
    }

    private function isValidSkinSize(int $width, int $height, string $type): bool
    {
        $isSkinWidthValid = false;
        $isSkinHeightValid = false;

        if ($type === 'skin') {
            $isSkinWidthValid = in_array($width, $this->validSkinSizes);
            $isSkinHeightValid = $height === $width || $height === $width / 2;
        } elseif ($type === 'cape') {
            $isSkinWidthValid = in_array($width, $this->validCapeSizes);
            $isSkinHeightValid = $height === $width / 2;
            if (!$isSkinWidthValid || !$isSkinHeightValid) {
                $isSkinWidthValid = $width === $this->additionalCapeSizes['width'];
                $isSkinHeightValid = $height === $this->additionalCapeSizes['height'];
            }
        }

        return $isSkinWidthValid && $isSkinHeightValid;
    }

    private function getSkinSize($skin): array
    {
        $skinImage = Image::make($skin->getPathname());

        return [
            'width' => $skinImage->width(),
            'height' => $skinImage->height()
        ];
    }
}
