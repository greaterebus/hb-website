#!/usr/bin/env python3
"""Batch-convert existing theme PNGs to WebP.

Unlike prepare-transparent-card-asset.py, this does no chroma-key removal -
it's for PNGs that are already clean (transparent or opaque). It just
re-encodes as WebP, optionally downscaling first when the source resolution
is far larger than the size the image is ever displayed at (small UI icons
exported at 600-800px but shown at 20-30px, etc).

Writes the .webp next to each source and leaves the source PNG in place -
delete the PNG yourself once references are repointed and the result is
eyeballed.
"""

from __future__ import annotations

import argparse
from pathlib import Path
from PIL import Image


def convert(input_path: Path, max_dimension: int | None, webp_quality: int) -> None:
    image = Image.open(input_path).convert("RGBA")
    original_dims = image.size

    if max_dimension and max(image.size) > max_dimension:
        ratio = max_dimension / max(image.size)
        new_size = (round(image.width * ratio), round(image.height * ratio))
        image = image.resize(new_size, Image.LANCZOS)

    output_path = input_path.with_suffix(".webp")
    image.save(output_path, "WEBP", lossless=False, quality=webp_quality, method=6)

    before = input_path.stat().st_size
    after = output_path.stat().st_size
    pct = 100 * (1 - after / before)
    dims_note = f"{original_dims[0]}x{original_dims[1]}"
    if image.size != original_dims:
        dims_note += f" -> {image.width}x{image.height}"

    print(
        f"{input_path.name}: {before/1024:,.1f} KB -> {after/1024:,.1f} KB "
        f"({pct:.0f}% smaller, {dims_note})"
    )


def main() -> None:
    parser = argparse.ArgumentParser(description="Convert PNGs to WebP, optionally downscaling first.")
    parser.add_argument("inputs", type=Path, nargs="+", help="Source PNG file(s)")
    parser.add_argument("--max-dimension", type=int, default=None, help="Cap the longest edge to this many pixels before encoding")
    parser.add_argument("--webp-quality", type=int, default=84)
    args = parser.parse_args()

    for input_path in args.inputs:
        convert(input_path, args.max_dimension, args.webp_quality)


if __name__ == "__main__":
    main()
