const fs = require("fs");
const path = require("path");
const TwigHeroiconDataGetter = require("./twigHeroiconDataGetter");

const pluginName = "TwigHeroiconPlugin";
const buildPath = "heroicons";

const defaultOpts = {
    templatePaths: ["templates"], // list of the paths of the root folders of the templates to look for
    defaultSize: "24", // can be 16 | 20 | 24
    defaultDisplayType: "outline", // can be solid | outline (only for size 24)
    importedHeroicons: [], // list of names of heroicons if importType is specific => defaultSize and defaultDisplayType will be used
    importType: 'twig', // can be 'twig' when looking heroicon(<icon-name>,...) into twig files or 'specific' if need to load specific icons and 'both' for both
    importedHeroicons: [] // used in combination with 'specific' importType => can be just an icon name (and the defaultSize and defaultDisplayType will be used) or an object like {name: string , displayType: string, size: string}
};

class TwigHeroiconPlugin {
    templatePaths;
    nodePath = "node_modules"
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

                    let heroiconsData = []
                    if (this.importType === 'twig' || this.importType === 'both'){
                        heroiconsData = [...heroiconsData, ...(new TwigHeroiconDataGetter()).getHeroiconTwigData(this.templatePaths.map(p => path.join(compiler.context, p)), this.defaultDisplayType, this.defaultSize)]
                    } if (this.importType === 'specific' || this.importType === 'both'){
                        this.importedHeroicons.forEach(iconData => {
                            if (typeof iconData === 'string') {
                                let iconDataDefaulted = {name: iconData, displayType: this.defaultDisplayType, size: this.defaultSize}
                                if (heroiconsData.indexOf(iconDataDefaulted) < 0) {
                                    heroiconsData.includes(iconDataDefaulted)
                                }
                            } 

                            if (typeof iconData === 'object' && 'name' in iconData && 'displayType' in iconData && 'size' in iconData ) {
                                if (heroiconsData.indexOf(iconData) < 0){
                                    heroiconsData.push(iconData)
                                }
                            } 
                        });
                    }

                    heroiconsData.forEach(data => {
                        let svgContent = fs.readFileSync(
                            getHeroiconFilePath(data.name, data.size, data.displayType),
                            "utf-8",
                        );
                        compilation.emitAsset(
                            path.join(buildPath, [data.name, data.displayType, data.size].join("-") + ".svg"),
                            new sources.RawSource(svgContent),
                        );

                    })
                },
            );
        });
    }
}

module.exports = TwigHeroiconPlugin;
