---
description: Review a pull request against CLAUDE.md, lint, tests, tenant safety, and PR hygiene
argument-hint: "[PR number or URL — defaults to the current branch's PR]"
allowed-tools: Bash(gh pr view:*), Bash(gh pr diff:*), Bash(gh pr checks:*), Bash(gh pr checkout:*), Bash(git diff:*), Bash(git log:*), Bash(git status:*), Bash(git fetch:*), Bash(vendor/bin/pint --test:*), Bash(vendor/bin/pest:*), Read, Grep, Glob
---

Review pull request $ARGUMENTS for this repo. If no PR is given, use the PR for the current branch.

## 1. Gather context

- `gh pr view $ARGUMENTS --json number,title,body,author,baseRefName,headRefName,files,additions,deletions,commits,labels`
- `gh pr diff $ARGUMENTS`
- `gh pr checks $ARGUMENTS`
- Read `CLAUDE.md` in full. Its rules are the review baseline.
- For each changed file, read enough of the surrounding code to judge the change in context (not just the diff hunk).

If the PR branch isn't checked out locally, say so and review from the diff only, unless I ask you to check it out.

## 2. Run the checks (when the PR branch is checked out)

- `vendor/bin/pint --test`: report every file that fails and which fixer.
- `vendor/bin/pest`: report failures with the test name and the assertion.
- Compare with `gh pr checks`. Call out if CI is red, pending, or missing.

Do not run `vendor/bin/pint` (the fixing form) or change any files. This is a read-only review.

## 3. Review checklist

Go through each item and mark ✅ / ⚠️ / ❌ with a one-line reason and `file:line` references.

**Tenant isolation (CLAUDE.md, highest priority)**
- New tenant-owned tables have `tenant_id` + FK; their models use `BelongsToTenant`.
- No `withoutGlobalScope(TenantScope::class)`, `withoutGlobalScopes()`, `DB::table(...)`, raw SQL, or `->toBase()` on tenant tables without written justification.
- `tenant_id` never comes from request input or mass assignment.
- No cross-tenant leakage in eager loads, relations to unscoped models, aggregates, caches (keys must include the tenant), queued jobs (must carry and restore the tenant), logs, or API responses.
- Route model binding still resolves after `IdentifyTenant`.
- There is a test proving tenant B can't read or modify tenant A's data for the new behavior.

**Correctness & code quality**
- Logic bugs, edge cases, null handling, error paths.
- Laravel idioms: validation via Form Requests or `$request->validate`, authorization, Eloquent over raw queries, no N+1 queries.
- Security: mass assignment (`$fillable`), XSS (`v-html`), CSRF, authz checks, secrets committed, `.env` changes.
- Naming, dead code, duplicated logic, and consistency with surrounding code.
- Migrations are reversible (`down()`), indexed where queried, and safe on existing data.
- Frontend: Vue props are typed, no unused imports, Inertia links/forms are used correctly.

**Scope (CLAUDE.md: keep changes small)**
- One concern per PR. Flag unrelated refactors, formatting churn, or a diff that should be split.
- Flag PRs over ~400 changed lines (excluding lockfiles and generated files).

**Tests**
- Behavior changes have Pest tests. Tests assert behavior, not implementation.
- Point out important untested paths.

**PR description & release plan**
- Title is clear and specific.
- Body follows `.github/pull_request_template.md`: What & why, How, Tenant impact, Testing, Release plan.
- The release plan covers migrations (and whether they're backwards compatible), env/config changes (and whether `.env.example` is updated), deploy order, feature flags, and rollback steps. If any are missing, say exactly what to add.

## 4. Output

Use this format:

```
## PR #<n>: <title>

**Verdict:** ✅ Approve | ⚠️ Approve with nits | ❌ Request changes

### Checks
- Pint: pass/fail (details)
- Pest: pass/fail (N passed, M failed)
- CI: green/red/pending

### Blocking issues
1. `path/file.php:42` – problem → suggested fix

### Non-blocking suggestions
- ...

### Checklist
| Area | Status | Notes |
|---|---|---|
| Tenant isolation | ✅/⚠️/❌ | ... |
| Correctness | ... | ... |
| Code style / Pint | ... | ... |
| Tests | ... | ... |
| Scope / size | ... | ... |
| PR description | ... | ... |
| Release plan | ... | ... |

### Suggested PR description additions
<only if the description or release plan is incomplete: paste-ready markdown>
```

Be specific and concise. Every blocking issue must have a file reference and a concrete fix. Don't pad the review with praise or restate the diff. Only post comments to GitHub if I explicitly ask.
