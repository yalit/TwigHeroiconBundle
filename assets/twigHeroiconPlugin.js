const fs = require("fs");
const path = require("path");
const getFiles = require("./getFiles");

const pluginName = "TwigHeroiconPlugin";
const buildPath = "heroicons";

const defaultOpts = {
  templatePaths: ["templates"], // list of the paths of the root folders of the templates to look for
  nodePath: "node_modules", // path of the node imported packages
  importType: "twig", // can be twig | specific | all-<16|20|24>-<solid|outline>
  importSize: "24", // can be 16 | 20 | 24
  importDisplayType: "outline", // can be solid | outline
  importedHeroicons: [], // list of names of heroicons if importType is specific
};

class TwigHeroiconPlugin {
  templatePaths;
  nodePath;
  importType;
  importSize;
  importDisplayType;
  importedHeroicons;

  constructor(opts = {}) {
    this.templatePaths = opts.templatePaths ?? defaultOpts.templatePaths;
    this.nodePath = opts.nodePath ?? defaultOpts.nodePath;
    this.importType = opts.importType ?? defaultOpts.importType;
    this.importSize = opts.importSize ?? defaultOpts.importSize;
    this.importDisplayType =
      opts.importDisplayType ?? defaultOpts.importDisplayType;
    this.importedHeroicons =
      opts.importedHeroicons ?? defaultOpts.importedHeroicons;
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
              importSize ?? this.importSize,
              importDisplayType ?? this.importDisplayType,
              filename + ".svg",
            );
          };

          const heroicons = new Set();
          getFiles(
            path.join(compiler.context, this.templatePaths[0]),
            "twig",
          ).forEach((file) => {
            const fileContent = fs.readFileSync(file, "utf8");
            const heroiconsData = this.getHeroiconData(fileContent);

            heroiconsData.forEach((data) => {
              if (heroicons.has(data.id)) {
                return;
              }

              let svgContent = fs.readFileSync(
                getHeroiconFilePath(data.name, data.size, data.displayType),
                "utf-8",
              );
              compilation.emitAsset(
                path.join(buildPath, data.id + ".svg"),
                new sources.RawSource(svgContent),
              );

              heroicons.add(data.id);
            });
          });
        },
      );
    });
  }

  /**
   * @return {id:string, name: string, displayType: string, size: string}[]
   **/
  getHeroiconData(fileContent) {
    const regex = /heroicon\( *('[^)]+') *\)/g;
    const heroicons = fileContent.matchAll(regex);

    const heroiconsData = [];
    heroicons.forEach((heroicon) => {
      let [name, displayType, size, _] = heroicon[1]
        .replace(/'/g, "")
        .split(",")
        .map((e) => e.trim());

      displayType = displayType !== "" ? displayType : this.importDisplayType;
      size = size !== "" ? size : this.importSize;
      heroiconsData.push({
        id: "-".join[(name, displayType, size)],
        name,
        displayType,
        size,
      });
    });

    return heroiconsData;
  }
}

module.exports = TwigHeroiconPlugin;
