const fs = require('fs');
const path = require('path');

class TwigHeroiconDataGetter
{
    /**
    * @return {name: string, displayType: string, size: string}[]
    */
    getHeroiconTwigData(templatePaths, defaultDisplayType = 'outline', defaultSize = '24'){
        const heroicons = new Set();
        const heroiconsFileData = [];
        templatePaths.forEach(p => {
            this.getFiles(p).forEach((file) => {
                    const fileContent = fs.readFileSync(file, "utf8");
                    const heroiconsData = this.getHeroiconData(fileContent, defaultDisplayType, defaultSize);

                    heroiconsData.forEach((data) => {
                        if (heroicons.has(data.id)) {
                            return;
                        }

                        heroicons.add(data.id);
                        heroiconsFileData.push({name: data.name, displayType: data.displayType, size: data.size})
                    });
                });
        });

        return heroiconsFileData
    }

    /**
   * @return {id:string, name: string, displayType: string, size: string}[]
   **/
    getHeroiconData(fileContent, defaultDisplayType, defaultSize) {
        const regex = /heroicon\( *('[^)]+') *\)/g;
        const heroicons = fileContent.matchAll(regex);

        const heroiconsData = [];
        heroicons.forEach((heroicon) => {
            let [name, displayType, size, _] = heroicon[1]
            .replace(/'/g, "")
            .split(",")
            .map((e) => e.trim());

            displayType = displayType !== "" ? displayType : defaultDisplayType;
            size = size !== "" ? size :defaultSize;
            heroiconsData.push({
                id: [name, displayType, size].join("-"),
                name,
                displayType,
                size,
            });
        });

        return heroiconsData;
    }

    getFiles(dirPath, allFilesPath = []) {
        fs.readdirSync(dirPath, {withFileTypes: true}).forEach((file) => {
            if (file.isDirectory()){
                allFilesPath = this.getFiles(path.join(dirPath, file.name), allFilesPath)
            } else {
                if (!file.name.endsWith(".twig")) return;
                allFilesPath.push(path.join(dirPath, file.name))
            }
        })

        return allFilesPath;
    }
}

module.exports = TwigHeroiconDataGetter
