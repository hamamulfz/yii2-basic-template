<?php

namespace app\helpers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRImage;
use Yii;

class CustomQRCode extends QRImage
{
    protected function drawModules(): void
    {
        $size = $this->matrix->size();

        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                if ($this->matrix->check($x, $y)) {
                    if ($this->isInFinderPattern($x, $y, $size)) {
                        $this->drawCircle($x, $y);
                    } else {
                        $this->drawSquare($x, $y);
                    }
                }
            }
        }
    }

    protected function isInFinderPattern($x, $y, $size): bool
    {
        // 7x7 squares are the standard "eyes"
        return (
            ($x >= 0 && $x <= 6 && $y >= 0 && $y <= 6) ||                            // Top-left
            ($x >= $size - 7 && $x <= $size - 1 && $y >= 0 && $y <= 6) ||           // Top-right
            ($x >= 0 && $x <= 6 && $y >= $size - 7 && $y <= $size - 1)              // Bottom-left
        );
    }

    protected function drawCircle($x, $y): void
    {
        $cx = $x * $this->scale + ($this->scale / 2);
        $cy = $y * $this->scale + ($this->scale / 2);
        $r = ($this->scale / 2) - 1;

        imagefilledellipse(
            $this->image,
            (int)$cx,
            (int)$cy,
            (int)($r * 2),
            (int)($r * 2),
            $this->moduleColor
        );
    }

    protected function drawSquare($x, $y): void
    {
        imagefilledrectangle(
            $this->image,
            $x * $this->scale,
            $y * $this->scale,
            ($x + 1) * $this->scale,
            ($y + 1) * $this->scale,
            $this->moduleColor
        );
    }

    public static function generate($text = '', $name = "QRCode")
    {
        try {

            $options = new QROptions([
                'version' => 5,
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'scale' => 10,
            ]);
            $path = "uploads/qr_$name.png";
            $path = strtolower($path);
            $savePath = Yii::getAlias("@baseweb/$path");
            if (!file_exists(dirname($savePath))) {
                mkdir(dirname($savePath), 0777, true);
            }
            $qrcode = new QRCode($options);
            $file = $qrcode->render($text, $path);
            // $imageData = $qrcode->render($text);
            // $base64 = base64_encode($imageData);
            $matrix = $qrcode->getQRMatrix($text);

            // Pass both options and matrix to CustomQRCode
            // $customOutput = new CustomQRCode($options, $matrix);
            // $imageData = $customOutput->dump();
            // var_dump($imageData);
            // Output the image
            // header('Content-type: image/png');
            // echo $customOutput->dump();
            return [
                'status' => true,
                'message' => 'QR Code generated successfully',
                'data' => [
                    'full_path' => $savePath,
                    'path' => $path,
                    'url' =>  Yii::$app->params['hostIntUrl'] . $path,
                    'name' => $name,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
