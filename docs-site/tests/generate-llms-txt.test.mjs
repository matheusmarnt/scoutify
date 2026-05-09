import { describe, it, expect } from 'vitest';
import { join } from 'node:path';
import { generateLlmsTxt } from '../scripts/generate-llms-txt.mjs';

const FIXTURE_DIR = join(import.meta.dirname, '__fixtures__', 'docs');
const TEMPLATE_PATH = join(import.meta.dirname, '../scripts/llms-template.md');

describe('generateLlmsTxt', () => {
  it('emits llms.txt with antipatterns block', async () => {
    const result = await generateLlmsTxt({ contentDir: FIXTURE_DIR, templatePath: TEMPLATE_PATH });
    expect(result.index).toContain('## Antipatterns');
    expect(result.index).toContain('icon_prefix');
    expect(result.index).toContain('button_in_anchor');
  });

  it('emits page index from fixture MDX', async () => {
    const result = await generateLlmsTxt({ contentDir: FIXTURE_DIR, templatePath: TEMPLATE_PATH });
    expect(result.index).toMatch(/- \[Installation\]/);
  });

  it('emits llms-full.txt with slug sections', async () => {
    const result = await generateLlmsTxt({ contentDir: FIXTURE_DIR, templatePath: TEMPLATE_PATH });
    expect(result.full).toContain('## getting-started/installation');
    expect(result.full).toContain('composer require matheusmarnt/scoutify');
  });

  it('full.txt contains index prepended', async () => {
    const result = await generateLlmsTxt({ contentDir: FIXTURE_DIR, templatePath: TEMPLATE_PATH });
    expect(result.full.indexOf('## Antipatterns')).toBeLessThan(result.full.indexOf('## getting-started/installation'));
  });
});
