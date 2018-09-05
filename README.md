This repository provides a copy of the Noto Emoji image set, optimized for the web.

<img width="40" height="40" src="https://cdn.jsdelivr.net/gh/s9e/emoji-assets-noto@11.0/dist/svgz/1f929.svgz">


## How to build

You will need Bash, php, npm and zopfli installed. Run `scripts/init.sh` to install git submodules and svgo, then run 
`scripts/build.sh` to rebuild the files in the `dist` directory. The process is single threaded and will take several minutes.


## Assets

Images in the `dist` directory are derived from the [Noto Emoji](https://github.com/googlei18n/noto-emoji) images.

Filenames have been normalized and the SVG content has been minified with [SVGO](https://github.com/svg/svgo/) before being compressed with [zopfli](https://github.com/google/zopfli).


## License

- Tools in the `scripts` directory are under the [MIT license](scripts/LICENSE).
- Images in the `dist` directory are under the [Apache license, version 2.0](dist/LICENSE) unless otherwise noted. See 
[dist/AUTHORS](dist/AUTHORS) for the list of authors. Flag images under third_party/behdad/region-flags are in the 
public domain or otherwise exempt from copyright ([more info](https://github.com/behdad/region-flags/blob/gh-pages/COPYING)).


## Differences from Noto Emoji

- SVG content has been minified with SVGO.
- Additional optimizations have been applied to the SVG files which may imperceptibly alter their appearance.
- SVG files have been compressed with zopfli to produce SVGZ files.
- Filenames have been normalized to only include the characters' codepoints, excluding U+200D and U+FE0F. For instance, the image named `emoji_u26f9_200d_2640.svg` in Noto Emoji is available as `26f9-2640.svgz` in this repository.
- Country and regional flags missing from the Noto Emoji repository have been sourced from [behdad/region-flags](https://github.com/behdad/region-flags). The dimensions of the images have been modified to fit the same 1:1 aspect ratio used in Noto Emoji. The aspect ratio of individual flags has been preserved. Flags images have been cropped to have rounded corners.


## Contributions

This repository is not open for external contributions. If you have a suggestion that pertains exclusively to this repository, you can open a new issue to discuss it. If you have a suggestion or a pull request that pertains to the art, please direct it to their author.
