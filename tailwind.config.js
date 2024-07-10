module.exports = {
  presets: [
    require('@marshallu/marsha-tailwind')
  ],
	plugins: [
		require("@tailwindcss/forms"),
  ],
	darkMode: 'class',
	content: require('fast-glob').sync([
		'./views/*.twig',
		'./views/*/*.twig',
	])
}
