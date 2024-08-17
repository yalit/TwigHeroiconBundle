const fs = require('fs');
const path = require('path');

class TwigHeroiconDataGetter
{
    /**
    * @return {name: string, displayType: string, size: string}[]
    */
    getHeroiconsData(templatePaths, defaultDisplayType = 'outline', defaultSize = '24'){
        const heroicons = new Set();
        const heroiconsData = [];
        templatePaths.forEach(p => {
            this.getFiles(p).forEach((file) => {
                    const fileContent = fs.readFileSync(file, "utf8");
                    const heroiconsData = this.getHeroiconData(fileContent, defaultDisplayType, defaultSize);

                    heroiconsData.forEach((data) => {
                        if (heroicons.has(data.id)) {
                            return;
                        }

                        heroicons.add(data.id);
                        heroiconsData.push({name: data.name, displayType: data.displayType, size: data.size})
                    });
                });
        });

        return heroiconsData
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
