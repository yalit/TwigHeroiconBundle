const TwigHeroiconPlugin = require("../../assets/twigHeroiconPlugin");

const twigTargetHeroicons = [
  { name: "plus", displayType: "outline", size: "24" },
  { name: "academic-cap", displayType: "solid", size: "24" },
  { name: "arrow-left", displayType: "solid", size: "20" },
  { name: "chevron-right", displayType: "outline", size: "24" },
];

const specificTargetHeroicons = [
  { name: "plus", displayType: "outline", size: "24" },
  { name: "academic-cap", displayType: "solid", size: "24" },
  { name: "arrow-left", displayType: "solid", size: "20" },
  { name: "chevron-right", displayType: "outline", size: "24" },
];

test("heroicons fetched correctly from twig files", () => {
  const plugin = new TwigHeroiconPlugin({
    templatePaths: ["../public/templates"], // list of the paths of the root folders of the templates to look for
  });

  expect(plugin.templatePaths).toEqual(["../public/templates"]);
  const heroiconsData = plugin.getHeroiconsData(__dirname);

  expect(heroiconsData).toMatchObject(twigTargetHeroicons);
});

test("heroicons fetched correctly from specific heroicons", () => {
  const importedHeroicons = [
    "plus",
    { name: "academic-cap", displayType: "solid", size: "24" },
    { name: "arrow-left", displayType: "solid", size: "20" },
    "chevron-right",
  ];

  const plugin = new TwigHeroiconPlugin({
    importType: "specific",
    importedHeroicons: importedHeroicons,
  });

  expect(plugin.importType).toEqual("specific");
  expect(plugin.importedHeroicons).toEqual(importedHeroicons);

  const heroiconsData = plugin.getHeroiconsData(__dirname);
  expect(heroiconsData).toMatchObject(specificTargetHeroicons);
});
