import { readFileSync, writeFileSync, mkdirSync } from 'node:fs';
import { join, relative, dirname, basename } from 'node:path';
import matter from 'gray-matter';
import fg from 'fast-glob';

/**
 * @param {{ contentDir: string, templatePath: string, outDir?: string }} opts
 * @returns {{ index: string, full: string }}
 */
export async function generateLlmsTxt({ contentDir, templatePath, outDir }) {
  const template = readFileSync(templatePath, 'utf8');
  const files = await fg('**/*.{md,mdx}', { cwd: contentDir, absolute: true });
  files.sort();

  const pages = files.map((file) => {
    const raw = readFileSync(file, 'utf8');
    const { data, content } = matter(raw);
    const relPath = relative(contentDir, file);
    const slug = relPath.replace(/\.(md|mdx)$/, '');
    const url = `https://matheusmarnt.github.io/scoutify/${slug}/`;
    return { slug, title: data.title ?? basename(slug), description: data.description ?? '', content, url };
  });

  const pageIndex = pages
    .map((p) => `- [${p.title}](${p.url})${p.description ? ` — ${p.description}` : ''}`)
    .join('\n');

  const groups = {};
  for (const p of pages) {
    const group = dirname(p.slug);
    if (!groups[group]) groups[group] = [];
    groups[group].push(p.slug);
  }
  const referenceMap = Object.entries(groups)
    .map(([g, slugs]) => `### ${g}\n${slugs.map((s) => `- ${s}`).join('\n')}`)
    .join('\n\n');

  const index = template
    .replace('{{PAGE_INDEX}}', pageIndex)
    .replace('{{REFERENCE_MAP}}', referenceMap);

  const pageSections = pages
    .map((p) => `---\n## ${p.slug}\n\n${p.content.trim()}`)
    .join('\n\n');

  const full = `${index}\n\n${pageSections}`;

  if (outDir) {
    mkdirSync(outDir, { recursive: true });
    writeFileSync(join(outDir, 'llms.txt'), index, 'utf8');
    writeFileSync(join(outDir, 'llms-full.txt'), full, 'utf8');
  }

  return { index, full };
}

// CLI usage: node generate-llms-txt.mjs --out <dir>
if (process.argv[1] === new URL(import.meta.url).pathname) {
  const outIdx = process.argv.indexOf('--out');
  const outDir = outIdx !== -1 ? process.argv[outIdx + 1] : undefined;
  generateLlmsTxt({
    contentDir: join(import.meta.dirname, '../src/content/docs'),
    templatePath: join(import.meta.dirname, 'llms-template.md'),
    outDir,
  }).then(({ index, full }) => {
    if (!outDir) {
      process.stdout.write(index);
    } else {
      console.log(`Written llms.txt (${index.length} chars) + llms-full.txt (${full.length} chars) to ${outDir}`);
    }
  });
}
