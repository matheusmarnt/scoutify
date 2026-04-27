# Contributing

Contributions are welcome! Please follow these steps:

## Development Setup

```bash
git clone git@github.com:matheusmarnt/scoutify.git
cd scoutify
composer install
```

## Running Tests

```bash
vendor/bin/pest --no-coverage --compact
```

## Code Style

This project uses [Laravel Pint](https://github.com/laravel/pint):

```bash
vendor/bin/pint
```

## Submitting a Pull Request

1. Fork the repository
2. Create a branch: `git checkout -b my-feature`
3. Make your changes with tests
4. Ensure tests pass and code style is clean
5. Commit using [Conventional Commits](https://www.conventionalcommits.org/)
6. Push and open a PR against `main`

## Commit Convention

This project uses Conventional Commits for automated changelogs:

| Prefix | Release bump |
|---|---|
| `feat:` | minor |
| `fix:` | patch |
| `feat!:` / `BREAKING CHANGE:` | major |
| `chore:` / `docs:` / `test:` / `ci:` | no release |

## License

By contributing, you agree that your contributions will be licensed under the [MIT License](LICENSE.md).
