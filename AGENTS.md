# Working on HugginButt

## Repository scope

- This repository is the custom WordPress child theme for HugginButt, built on Kadence with WooCommerce.
- Run Git commands from this directory: `wordpress/wp-content/themes/hugginbutt-child`.
- GitHub remote: https://github.com/greaterebus/hb-website
- Read `README.md` for the theme structure, branding, and setup. Read `assets/images/ASSETS.md` before changing image assets, and keep that inventory current.
- WordPress core, parent themes, plugins, uploads, and the database are outside this repository. Implement website customizations in the child theme where possible.

## Hosting and preview

- The website is hosted on a Synology NAS using Docker. Domain traffic is routed through the Cloudflare Docker container.
- The parent `hugginbutt/docker-compose.yml` defines WordPress and MariaDB. WordPress mounts `./wordpress` into `/var/www/html`, so edits to the mounted theme can affect the running website immediately. A Git push is not a separate deployment step for those edits.
- The website is currently under construction. Use the owner-provided view-access link to inspect the storefront:
  https://hugginbutt.com/?woo-share=iwW73NSFN8EyyIVnXKGsRKhjbUpTyxF8
- Keep the under-construction setting in place unless the owner requests a change. The preview link grants view access; it does not provide administrator access.
- Keep infrastructure changes separate from theme work unless the task calls for them. Do not copy database credentials or other infrastructure secrets into this repository.

## Editing and validation

- Check `git status` before editing. Preserve existing local changes and keep unrelated work out of commits.
- Follow the existing theme structure and branding. CSS and JavaScript are hand-authored; there is no build step.
- Validate the affected behavior before committing. For PHP changes, run syntax checks with an available PHP runtime. For visual changes, inspect the affected pages at desktop and mobile sizes using the preview link.
- For WooCommerce changes, check the relevant product, cart, or checkout flow. Do not place real orders or trigger payments just to test a change.
- Review the diff and run `git diff --check`. Report any validation that could not be completed.

## Commits and pushes

- Commits should represent stable, coherent units of code. Finish and validate a unit of work, then commit and push it to the appropriate branch.
- Avoid checkpoint commits containing broken or unfinished work. Keep unrelated changes in separate commits.
- Use short, direct commit messages describing the change, such as `Fix mobile product card spacing` or `Document theme development workflow`.
- Do not add emojis, task lists, AI attribution, generated-by boilerplate, or other filler to commit messages.
- Stage only the intended files or hunks, review the staged diff, and preserve unrelated local changes.
- Check the current branch and remote before pushing. Do not force-push or rewrite shared history unless explicitly requested.
