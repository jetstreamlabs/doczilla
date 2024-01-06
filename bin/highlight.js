import { getHighlighter } from 'shikiji';
import { transformerNotationDiff } from 'shikiji-transformers';
import { transformerCompactLineOptions } from 'shikiji-transformers';
import { transformerNotationFocus } from 'shikiji-transformers';
import { transformerNotationHighlight } from 'shikiji-transformers';
import { transformerNotationErrorLevel } from 'shikiji-transformers';

const [, , jsonArray] = process.argv;

async function highlightCodeBlock(code, language, themes, highlighter) {
  try {
    return await highlighter.codeToHtml(code, {
      lang: language,
      themes: {
        light: themes[0],
        dark: themes[1],
      },
      transformers: [
        transformerNotationDiff(),
        transformerCompactLineOptions(),
        transformerNotationFocus(),
        transformerNotationHighlight(),
        transformerNotationErrorLevel(),
      ],
    });
  } catch (error) {
    console.error(`Error highlighting language ${language}: ${error.message}`);
    return code;
  }
}

async function processCode(code, language, themes) {
  const highlighter = await getHighlighter({
    themes: themes,
    langs: [language],
  });

  return highlightCodeBlock(code, language, themes, highlighter);
}

try {
  const args = JSON.parse(jsonArray);
  const codeToHighlight = args[0];
  const language = args[1] || 'plaintext'; // Default to 'plaintext' if language is not specified
  const themes = args[2].split(':'); // Splitting the theme string into an array

  processCode(codeToHighlight, language, themes)
    .then((highlightedCode) => {
      process.stdout.write(highlightedCode);
    })
    .catch((error) => {
      process.stderr.write(`Error processing code: ${error.message}\n`);
      process.exit(1);
    });
} catch (error) {
  process.stderr.write(`Error parsing JSON: ${error.message}\n`);
  process.exit(1);
}
