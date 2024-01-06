# Contributing to Doczilla

### Actively participate in the development and the future of Doczilla by contributing regularly.

Open Source projects are maintained and backed by a **wonderful community** of users and collaborators.

We encourage you to actively participate in the development and future of Doczilla by contributing to the source code, improving documentation, reporting potential bugs or testing new features.

### Channels

There are a couple ways to take part in the Doczilla community.

1. <a href="https://github.com/jetstreamlabs/doczilla" rel="nofollow" target="_blank">Github Repositories</a>: Report bugs or create feature requests against the dedicated Doczilla repository.
2. <a href="https://twitter.com/doczilla" rel="nofollow" target="_blank">Twitter/X</a>: Stay in touch with the progress we make and learn about the awesome things happening around Doczilla.

## Using the issue tracker

The [issue tracker](https://github.com/jetstreamlabs/doczilla/issues) is
the preferred channel for [bug reports](#bug-reports), [features requests](#feature-requests)
and [submitting pull requests](#pull-requests), but please respect the following
restrictions:

- Please **do not** use the issue tracker for personal support requests.

- Please **do not** get off track in issues. Keep the discussion on topic and
  respect the opinions of others.

- Please **do not** post comments consisting solely of "+1" or ":thumbsup:".
  Use [GitHub's "reactions" feature](https://github.com/blog/2119-add-reactions-to-pull-requests-issues-and-comments)
  instead. We reserve the right to delete comments which violate this rule.

## Issues and labels

Our bug tracker utilizes several labels to help organize and identify issues. Here's what they represent and how we use them:

- `bug` - Issues that are reported to us, but actually are the result of a code-specific bug not stemming from a project dependecy package.
- `confirmed` - Issues that have been confirmed with a reduced test case and identify a bug in Doczilla.
- `css` - Issues with compiled CSS or source TailwindCSS files.
- `js` - Issues with compiled or source JavaScript files.
- `docs` - Issues for improving or updating our documentation.
- `feature` - Issues asking for a new feature to be added, or an existing one to be extended or modified. New features require a minor version bump (e.g., `v1.0.0` to `v1.1.0`).
- `help wanted` - Issues where we need or would love help from the community to resolve.
- `meta` - Issues with the project itself or our GitHub repository.

For a complete look at our labels, see the [project labels page](https://github.com/jetstreamlabs/doczilla/labels).

## Bug reports

A bug is a _demonstrable problem_ that is caused by the code in the repository.
Good bug reports are extremely helpful, so thanks!

Guidelines for bug reports:

1. Provide a clear title and description of the issue.
2. Share the version of Doczilla you are using.
3. Add code examples to demonstrate the issue. You can also provide a complete repository to reproduce the issue quickly.

A good bug report shouldn't leave others needing to chase you down for more information. Please try to be as detailed as possible in your report:

- What is your environment?
- What steps will reproduce the issue?
- What browser(s) and OS experience the problem?
- Do other browsers show the bug differently?
- What would you expect to be the outcome?

All these details will help us fix any potential bugs. Remember, fixing bugs takes time. We're doing our best!

Example:

> Short and descriptive example bug report title
>
> A summary of the issue and the browser/OS environment in which it occurs. If
> suitable, include the steps required to reproduce the bug.
>
> 1. This is the first step
> 2. This is the second step
> 3. Further steps, etc.
>
> `<url>` - a link to the reduced test case
>
> Any other information you want to share that is relevant to the issue being
> reported. This might include the lines of code that you have identified as
> causing the bug, and potential solutions (and your opinions on their
> merits).

## Feature requests

Feature requests are welcome! When opening a feature request, it's up to _you_ to make a strong case to convince the project's developers of the merits of this feature. Please provide as much detail and context as possible.

When adding a new feature to the library, make sure you update the documentation package as well.

### Testing

Before providing a pull request be sure to test the feature you are adding. Doczilla's target code coverage is 100% and we're proudly consistent with that.

<img src="https://raw.githubusercontent.com/jetstreamlabs/doczilla/1.x/.github/img/coverage-badge.svg" alt="Code Coverage" />

## Local Development

Local development of Doczilla requires an Nginx webserver running PHP >= 8.2, the current LTS versions of Node.js, a cache server that Laravel supports, and optimally utilizing Laravel Octane.

Setting up your local environment is outside the scope of this document as there a many different setups for each operating system, from WAMP packages for Windows, Laravel Valet or Herd for Mac and of course Docker. Just ensure your version of PHP is compatible with that listed above.

All topic branches are created off of the `main` branch of the repository. So you will only need to keep the `main` branch locally, and in your fork.

1. [Fork](https://help.github.com/fork-a-repo/) the project, clone your fork, and configure the remotes:

```bash
# Clone your fork of the repo into the current directory
git clone https://github.com/<your-username>/doczilla.git

# Navigate to the newly cloned directory
cd doczilla

# Assign the original repo to a remote called "upstream"
git remote add upstream https://github.com/jetstreamlabs/doczilla.git
```

2. Checkout the `main` branch from upstream in order to pull down the branch locally:

```bash
git checkout -b main upstream/main
```

3. With the `main` branch checked out, install all composer and node dependencies.

```bash
composer install && npm install
```

4. Doczilla uses [Husky](https://typicode.github.io/husky) to manage code style corrections and the checking of commit messages via Git-Hooks. The actual husky hooks are not commited to the repo so you need to install them manually when setting up your project.

```bash
npx husky add .husky/commit-msg 'npx --no-install commitlint --edit $1'

npx husky add .husky/pre-commit 'npm run fix'
```

5. If your webserver is running on secure SSL, which is recommended, ensure you adjust the `.env` file and set the following variable:

```bash
-APP_URL="https://${APP_DOMAIN}"
+APP_URL="http://${APP_DOMAIN}"
```

6. Symlink storage, install demo docs, cache and index the docs, and build assets.

```bash
# symlink the storage directory:
php artisan storage:link

# pull in the latest demo docs:
php artisan docs:latest

# if DOCS_CACHE=true in .env, cache the docs:
php artisan docs:cache

# index the docs for search:
php artisan docs:index

# build the assets
npm run build
```

7. You should now be able to run Doczilla at the domain you registered in `.env`, or run the app in dev mode:

```bash
npm run dev
```

## Pull requests

Good pull requests—patches, improvements, new features are a fantastic help. They should remain focused in scope and avoid containing unrelated commits.

**Please ask first** before starting on any significant pull request (e.g. implementing features, refactoring code, porting to a different language), otherwise you might spend a lot of time working on something that the project's developers might not want to merge into the project.

Please adhere to the [coding guidelines](#code-guidelines) used throughout the project (indentation, accurate comments, etc.) and any other requirements (such as test coverage).

Adhering to the following process is the best way to get your work included in the project:

1. First, fork the repository and create a branch as specified in [Local Development](#local-development) above.

2. Create a new topic branch (off the `main` project branch) to contain your feature, change, or fix:

```bash
git checkout -b <topic-branch-name>
```

3. Make sure your commits are logically structured. Please adhere to these [git commit message guidelines](#git-commit-message-guidelines). Use Git's [interactive rebase](https://help.github.com/en/github/using-git/about-git-rebase) feature to tidy up your commits before making them public.

4. Ensure that you've written tests that pass and provide 100% coverage for any code you've added to your topic branch. **IMPORTANT** pull requests submitted without tests or less than 100% coverage will be rejected and deleted.

5. Locally rebase the upstream main branch into your topic branch:

```bash
git pull --rebase upstream main
```

6. Push your topic branch up to your fork:

```bash
git push origin <topic-branch-name>
```

7. [Open a Pull Request](https://help.github.com/articles/using-pull-requests/) with a clear title and description against the `main` branch.

**Important!** By submitting a patch, you agree to allow the project owners to
license your work under the terms of the [MIT License](./LICENSE.md).

## Code guidelines

### PHP Code Fixing

The Builder uses [Pinte](https://github.com/jetstreamlabs/pinte) to style the PHP code within the application. Pinte uses [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) as a low-level tool to handle this, and it's configured via the `pinte.json` file in the document root.

Pinte uses the `laravel` preset, and an indent of 2 spaces.

### Default Code Fixing

HPD Builder uses [Prettier](https://prettier.io) to fix various different file types which you can see in the `.prettierrc` config file in the Builder document root.

Prettier is installed in the Builder toolchain via `npm` and is run on all file types declared in the `.prettierrc` file when the `pre-commit` hook is run on commit.

## Git Commit Message Guidelines

Doczilla utilizes [Conventional Commits](https://www.conventionalcommits.org) via the [commitlint](https://commitlint.js.org) family of packages to ensure proper structuring of commit messages. Adding this standardization allows us to automate Changelog generation, and [Semantic Versioning](https://semver.org) releases based on the commit messages you as a developer write when you commit.

This standard is enforced via the `.husky/commit-msg` git-hook that you installed in the [Local Development Guidelines](#local-development) section earlier in the document.

Commit messages must follow the following format:

```bash
type(scope?): subject
body?
footer?
```

Doczilla uses in-house commit types to generate Semver releases. Here you can see the commit type, and the type of release it creates:

| Commit Type | Release Created |
| :---------: | :-------------: |
| `breaking`  |    **MAJOR**    |
|   `feat`    |    **MINOR**    |
|    `fix`    |    **PATCH**    |
| `refactor`  |    **PATCH**    |
|   `docs`    |    **PATCH**    |
|   `task`    |    **PATCH**    |
|    `wip`    |      false      |
|   `chore`   |      false      |

We encourage you to read the [Conventional Commits](https://www.conventionalcommits.org) documentation to get a better understanding of the standard.

## License

By contributing your code, you agree to license your contribution under the [MIT License](https://github.com/jetstreamlabs/doczilla/tree/main/LICENSE).
By contributing to the documentation, you agree to license your contribution under the [Creative Commons Attribution 3.0 Unported License](https://github.com/jetstreamlabs/doczil.la/tree/main/LICENSE).
