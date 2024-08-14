const fs = require('fs');
const path = require('path');

function getFiles(dirPath, extension = "*", allFilesPath = []) {
    fs.readdirSync(dirPath, {withFileTypes: true}).forEach((file) => {
        if (file.isDirectory()){
            allFilesPath = getFiles(path.join(dirPath, file.name), extension, allFilesPath)
        } else {
            if (extension !== "*" && !file.name.endsWith(extension)) return;
            allFilesPath.push(path.join(dirPath, file.name))
        }
    })

    return allFilesPath;
}


module.exports = getFiles;
