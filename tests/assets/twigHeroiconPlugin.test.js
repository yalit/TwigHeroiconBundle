const TwigHeroiconPlugin = require('../../assets/twigHeroiconPlugin');

test('heroicons fetched correctly from twig files', () => {
    const plugin = new TwigHeroiconPlugin({
        templatePaths: ["../public/templates"], // list of the paths of the root folders of the templates to look for
    })

    expect(plugin.templatePaths).toEqual(["../public/templates"]);
});
