class SpecificHeroiconDataGetter {
  /**
   * @return {name: string, displayType: string, size: string}[]
   */
  getHeroiconsData(
    specificHeroicons,
    defaultDisplayType = "outline",
    defaultSize = "24",
  ) {
    const heroiconsData = [];

    specificHeroicons.forEach((iconData) => {
      if (typeof iconData === "string") {
        let iconDataDefaulted = {
          name: iconData,
          displayType: defaultDisplayType,
          size: defaultSize,
        };
        if (heroiconsData.indexOf(iconDataDefaulted) < 0) {
          heroiconsData.push(iconDataDefaulted);
        }
      }

      if (
        typeof iconData === "object" &&
        "name" in iconData &&
        "displayType" in iconData &&
        "size" in iconData
      ) {
        if (heroiconsData.indexOf(iconData) < 0) {
          heroiconsData.push(iconData);
        }
      }
    });
    return heroiconsData;
  }
}

module.exports = SpecificHeroiconDataGetter;
