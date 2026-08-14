<?php

namespace App\Services;

use App\Models\Asset;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class AssetQrCodeService
{
    /**
     * Gera um QR Code em SVG para a URL interna e autenticada do ativo.
     */
    public function toSvg(Asset $asset, int $size = 320): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, 12),
            new SvgImageBackEnd(),
        );

        return (new Writer($renderer))->writeString($asset->qrCodeUrl());
    }
}
