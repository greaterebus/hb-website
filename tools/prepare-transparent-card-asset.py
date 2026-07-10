#!/usr/bin/env python3
"""Prepare generated shop-card art for theme use.

Takes chroma-key PNGs from image generation, removes the flat green
background, despills green edge pixels, crops to visible pixels, adds
consistent padding, and writes a transparent PNG plus optimized WebP.
"""

from __future__ import annotations

import argparse
from pathlib import Path
from PIL import Image


def parse_hex_color(value: str) -> tuple[int, int, int]:
    value = value.strip().lstrip("#")
    if len(value) != 6:
        raise argparse.ArgumentTypeError("Expected a 6-digit hex color")
    return tuple(int(value[i : i + 2], 16) for i in (0, 2, 4))


def color_distance(pixel: tuple[int, int, int, int], key: tuple[int, int, int]) -> int:
    r, g, b, _ = pixel
    return max(abs(r - key[0]), abs(g - key[1]), abs(b - key[2]))


def despill_green(pixel: tuple[int, int, int, int]) -> tuple[int, int, int, int]:
    r, g, b, a = pixel
    if a == 0:
        return (r, g, b, 0)
    # Green-screen antialiasing leaves yellow-green halos. Clamp green so it
    # cannot dominate the warm parchment edge pixels.
    max_warm_green = max(r, b) + 8
    if g > max_warm_green:
        g = max_warm_green
    return (r, g, b, a)


def remove_key(image: Image.Image, key: tuple[int, int, int], threshold: int, feather: int) -> Image.Image:
    rgba = image.convert("RGBA")
    cleaned = []
    for pixel in rgba.getdata():
        distance = color_distance(pixel, key)
        r, g, b, a = pixel
        if distance <= threshold:
            cleaned.append((r, g, b, 0))
            continue
        if feather > 0 and distance <= threshold + feather:
            alpha = int(a * (distance - threshold) / feather)
            cleaned.append(despill_green((r, g, b, alpha)))
            continue
        cleaned.append(despill_green(pixel))
    rgba.putdata(cleaned)
    return rgba


def crop_alpha(image: Image.Image, padding: int, alpha_floor: int) -> Image.Image:
    alpha = image.getchannel("A")
    mask = alpha.point(lambda value: 255 if value >= alpha_floor else 0)
    bbox = mask.getbbox()
    if bbox is None:
        raise ValueError("No visible pixels after chroma-key removal")

    left, top, right, bottom = bbox
    left = max(0, left - padding)
    top = max(0, top - padding)
    right = min(image.width, right + padding)
    bottom = min(image.height, bottom + padding)
    return image.crop((left, top, right, bottom))


def erase_faint_alpha(image: Image.Image, alpha_floor: int) -> Image.Image:
    rgba = image.convert("RGBA")
    pixels = []
    for r, g, b, a in rgba.getdata():
        if a < alpha_floor:
            pixels.append((r, g, b, 0))
        else:
            pixels.append((r, g, b, a))
    rgba.putdata(pixels)
    return rgba


def main() -> None:
    parser = argparse.ArgumentParser(description="Remove chroma key, crop, and export transparent product-card art.")
    parser.add_argument("input", type=Path)
    parser.add_argument("--out", type=Path, required=True, help="Output path without extension, or with .png/.webp extension")
    parser.add_argument("--key", type=parse_hex_color, default=parse_hex_color("#00ff00"))
    parser.add_argument("--threshold", type=int, default=82)
    parser.add_argument("--feather", type=int, default=72)
    parser.add_argument("--padding", type=int, default=8)
    parser.add_argument("--alpha-floor", type=int, default=24)
    parser.add_argument("--webp-quality", type=int, default=84)
    args = parser.parse_args()

    output_base = args.out.with_suffix("")
    output_base.parent.mkdir(parents=True, exist_ok=True)

    source = Image.open(args.input)
    keyed = remove_key(source, args.key, args.threshold, args.feather)
    keyed = erase_faint_alpha(keyed, args.alpha_floor)
    cropped = crop_alpha(keyed, args.padding, args.alpha_floor)

    png_path = output_base.with_suffix(".png")
    webp_path = output_base.with_suffix(".webp")
    cropped.save(png_path, optimize=True)
    cropped.save(webp_path, "WEBP", lossless=False, quality=args.webp_quality, method=6)

    print(f"wrote {png_path} {cropped.width}x{cropped.height}")
    print(f"wrote {webp_path} {cropped.width}x{cropped.height}")


if __name__ == "__main__":
    main()