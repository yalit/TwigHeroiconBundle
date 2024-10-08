const fs = require("fs");
const path = require("path");
const TwigHeroiconDataGetter = require("./twigHeroiconDataGetter");
const SpecificHeroiconDataGetter = require("./specificHeroiconDataGetter");

const pluginName = "TwigHeroiconPlugin";
const buildPath = "heroicons";

const defaultOpts = {
  templatePaths: ["templates"], // list of the paths of the root folders of the templates to look for
  defaultSize: "24", // can be 16 | 20 | 24
  defaultDisplayType: "outline", // can be solid | outline (only for size 24)
  importType: "twig", // can be 'twig' when looking heroicon(<icon-name>,...) into twig files or 'specific' if need to load specific icons and 'both' for both
  importedHeroicons: [], // used in combination with 'specific' importType => can be just an icon name (and the defaultSize and defaultDisplayType will be used) or an object like {name: string , displayType: string, size: string}
};

class TwigHeroiconPlugin {
  templatePaths;
  nodePath = "node_modules";
  defaultSize;
  defaultDisplayType;
  importedHeroicons;
  importType;

  constructor(opts = {}) {
    this.templatePaths = opts.templatePaths ?? defaultOpts.templatePaths;
    this.defaultSize = opts.importSize ?? defaultOpts.defaultSize;
    this.defaultDisplayType =
      opts.importDisplayType ?? defaultOpts.defaultDisplayType;
    this.importedHeroicons =
      opts.importedHeroicons ?? defaultOpts.importedHeroicons;
    this.importType = opts.importType ?? defaultOpts.importType;
  }

  apply(compiler) {
    compiler.hooks.compilation.tap(pluginName, (compilation) => {
      compilation.hooks.processAssets.tap(
        {
          name: pluginName,
          stage: compilation.PROCESS_ASSETS_STAGE_ADDITIONAL,
        },
        () => {
          const compiler = compilation.compiler;
          // using answer and comments from here : https://stackoverflow.com/questions/72652370/webpack-4-to-5-custom-plugin-replacing-compilation-assets-mutation-with-compil
          const sources = compiler.webpack.sources;

          const getHeroiconFilePath = (
            filename,
            importSize = undefined,
            importDisplayType = undefined,
          ) => {
            return path.join(
              compiler.context,
              this.nodePath,
              "heroicons",
              importSize ?? this.defaultSize,
              importDisplayType ?? this.defaultDisplayType,
              filename + ".svg",
            );
          };

          this.getHeroiconsData(compiler.context).forEach((data) => {
            let svgContent = fs.readFileSync(
              getHeroiconFilePath(data.name, data.size, data.displayType),
              "utf-8",
            );
            compilation.emitAsset(
              path.join(
                buildPath,
                [data.name, data.displayType, data.size].join("-") + ".svg",
              ),
              new sources.RawSource(svgContent),
            );
          });
        },
      );
    });
  }

  /**
   * @return {name: string, displayType: string, size: string}[]
   */
  getHeroiconsData(rootDir) {
    let heroiconsData = [];
    if (this.importType === "twig" || this.importType === "both") {
      heroiconsData = [
        ...heroiconsData,
        ...new TwigHeroiconDataGetter().getHeroiconsData(
          this.templatePaths.map((p) => path.join(rootDir, p)),
          this.defaultDisplayType,
          this.defaultSize,
        ),
      ];
    }
    if (this.importType === "specific" || this.importType === "both") {
      const specificHeroiconsData =
        new SpecificHeroiconDataGetter().getHeroiconsData(
          this.importedHeroicons,
          this.defaultDisplayType,
          this.defaultSize,
        );

      specificHeroiconsData.forEach((data) => {
        if (heroiconsData.indexOf(data) < 0) {
          heroiconsData.push(data);
        }
      });
    }

    return heroiconsData;
  }
}

module.exports = TwigHeroiconPlugin;
