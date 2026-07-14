# AI Agent Rules

This repository is a Laravel 8 monolith with modular business domains, MongoDB,
GraphQL, Blade-rendered pages, and React/TypeScript islands.

The codebase contains both newer conventions and legacy shortcuts. Do not assume
nearby code is the right pattern. Prefer the documented conventions in this file
and in `docs/*.md`; copy legacy code only when compatibility requires it.

---

# Stack

Backend:
- Laravel 12
- PHP 8.5.4+ compatibility
- MongoDB via Jenssegers
- Rebing GraphQL
- Multi-tenant architecture via `stancl/tenancy`

Frontend:
- React
- TypeScript
- Tailwind CSS where available
- Zustand where shared client state is needed
- Lexical Editor in teacher question/editor flows
- Radix UI where available
- Legacy Blade, SCSS, and plain JS still exist

---

# Current Codebase Map

Primary folders:
- `app/`: Laravel application shell, providers, middleware, exceptions, base
  controllers, console commands, and shared models/rules.
- `core/`: shared OLM foundation: base model/repository/service abstractions,
  GraphQL base classes, DTO/object primitives, middleware, facades, traits,
  Redis queue handlers, Elasticsearch helpers, and Blade view components.
- `modules/`: business domains. Most feature work belongs here.
- `packages/`: reusable integrations, SDK-like packages, infrastructure
  adapters, and cross-domain package providers.
- `resources/ts/`: shared frontend layer: UI components, hooks, utilities,
  types, constants, React root helpers, and small shared stores.
- `resources/modules/`: frontend feature modules aligned with backend domains.
- `tailwind/`: OLM Tailwind tokens, CSS variables, custom plugins, and
  component class documentation.

Common module shape:
- `Controller/`: HTTP entrypoints. Keep thin.
- `Request/`: FormRequest validation and request normalization.
- `Service/` and `Service/Impl/`: business workflows and side effects.
- `Repository/` and `Repository/Impl/`: query persistence boundaries.
- `Model/`: Mongo/Eloquent models.
- `Object/`: DTO/filter/value objects.
- `Policy/`: authorization rules.
- `View/`: Blade views and TS/TSX/JS/CSS assets.
- `Provider.php`, `routes.php`, `config.php`: module bootstrapping.

Common core shape:
- `Repository/`: base repository contracts and implementations.
- `Service/`: reusable base services and infrastructure services.
- `Model/`: base Mongo model, casts, and relation helpers.
- `Object/`: shared DTO/value objects such as pagination and option objects.
- `Middleware/`: shared auth/role/access middleware.
- `View/Components/`: shared Blade components.
- `RedisQueue/`: queue handler contract, factory, and handlers.
- `Facade/`, `Traits/`, `Utility/`, `ElasticSearch/`: shared support code.

Common frontend module shape:
- `index.tsx`: feature entrypoint and Blade-to-React view map.
- `views/`: page-level React roots.
- `components/`: feature components.
- `hooks/`: feature orchestration and side effects.
- `services/`: typed API/client functions.
- `stores/`: focused Zustand stores for shared feature state.
- `types/`: props, and domain view types.
- `api/`: API.
- `utils/`, `constants/`, `contexts/`, `styles/`: supporting code when needed.

---

# Pattern Classification

Approved patterns:
- Thin controller or GraphQL resolver delegates to FormRequest, Service, and
  Repository/Query layer.
- Services accept explicit data, DTOs, IDs, and actor context instead of reading
  `Request` or global state directly.
- Repositories own complex Mongo queries and aggregation pipelines.
- FormRequest owns validation, authorization input checks, and small request
  normalization helpers.
- DTO/Object classes carry workflow inputs, filters, and operation outcomes.
- Policies centralize authorization decisions.
- Packages expose service contracts/adapters and hide third-party protocol
  details behind package services.
- Core abstractions are used as shared primitives only; business behavior still
  belongs in the owning module or package.
- Frontend modules use `views/components/hooks/services/stores/types` and mount
  through `resources/ts/utils/initReactRoot` or a typed `VIEW_MAP`.
- Shared UI uses `resources/ts/components`, Radix wrappers, `cn`, `clsx`,
  `tailwind-merge`, and `class-variance-authority` where practical.

Legacy patterns:
- Very large controllers or services that mix validation, authorization, query
  building, business rules, response formatting, and rendering.
- Autoloaded `Func.php` files and broad global helper usage.
- `core/Func.php` and large `core/Helper.php` usage as extension points for new
  domain behavior.
- Blade files containing authorization, request parsing, queries, or business
  decisions.
- Direct Mongo/DB query duplication across controllers, services, views, and
  GraphQL resolvers.
- React/TSX files with many unrelated responsibilities, repeated `useEffect`
  synchronization, and API logic inside component render flow.
- Lexical plugins that mix editor state, toolbar UI, data conversion, and DOM
  manipulation in one file.
- Large repeated Tailwind or utility class chains instead of reusable UI
  variants/components.
- Direct `ReactDOM.createRoot` calls scattered in feature files when
  `initReactRoot` would manage mount/unmount consistently.
- Direct `window._api*` calls from views/components instead of typed
  `services/` wrappers.
- jQuery/global DOM utilities in shared scripts and legacy TS files.

Unknown patterns:
- Code in a domain not covered by current docs.
- One-off integration behavior without tests or clear owner.
- Existing code that contradicts these docs but cannot yet be safely changed.

When a pattern is unknown, inspect the owning module, prefer the approved
direction, and document any compatibility compromise in the change summary.

---

# Before Coding

1. Analyze related files across `app/`, the owning `modules/<domain>/`, and any
   touched `packages/<package>/` or `core/` primitive.
2. Classify the surrounding pattern as approved, legacy, or unknown.
3. Propose the implementation approach briefly before editing when the change is
   substantial.
4. Implement the smallest maintainable change that improves the touched area.
5. Review the result against `docs/backend-conventions.md`,
   `docs/module-boundaries.md`, and `docs/legacy-patterns.md`.

---

# Global Principles

- Readability over cleverness.
- Reusable code over duplicated code.
- Explicit typing over implicit typing where PHP/TS compatibility allows it.
- Small focused modules over giant files.
- Composition over inheritance.
- Predictable state flow.
- Compatibility-aware refactoring over risky rewrites.
- Documented conventions over nearby legacy examples.

---

# Backend Rules

- Keep controllers and GraphQL resolvers thin.
- Use FormRequest for validation and request normalization.
- Put business workflows in Services.
- Put complex reads, writes, and aggregations in Repository/Query classes.
- Use DTO/Object classes for multi-field inputs and operation results.
- Use Policy classes for authorization that is reused or non-trivial.
- Use explicit transactions for multi-step writes when supported by the
  connection and operation.
- Keep tenant scope explicit in query, cache, queue, storage, and integration
  code.
- Avoid duplicated Mongo aggregation pipelines.
- Avoid direct DB queries in Blade, controllers, and GraphQL types.
- Do not introduce new global helpers. If a helper already exists, wrap or
  isolate usage behind a typed service when touching the code.
- Extend `core` only for genuinely shared framework-level behavior. Keep module
  business rules out of `core`.
- Prefer module-specific repositories/services over adding behavior to
  `Core\Repository\BaseRepository` or `Core\Service\BaseService`.

---

# Frontend Rules

- Use functional React components with explicit TypeScript props.
- Put shared frontend primitives in `resources/ts`; put feature-specific code in
  `resources/modules/`.
- Prefer `initReactRoot`/`destroyReactRoot` for React islands that may remount.
- Keep API access, state transitions, and derived data outside render-heavy
  components.
- Route API calls through module `services/` or shared API helpers; do not add
  new direct `window._api*` calls inside large views/components.
- Extract reusable hooks for repeated data loading, subscriptions, and command
  logic.
- Store only shared/global client state in Zustand; keep local UI state local.
- Use selectors for Zustand reads to avoid unnecessary re-renders.
- Avoid `useEffect` for derived state; derive during render or use `useMemo`
  when computation is expensive.
- Split large TSX files by responsibility: page shell, data hook, table/list,
  dialog/form, row/item, and shared UI.
- Keep Blade-to-React handoff explicit through typed props.
- Keep jQuery, Select2, Bootstrap, and global DOM helpers isolated when legacy
  pages still require them.

---

# Tailwind/UI Rules

- Use the `tw-` prefix for Tailwind utilities in this project.
- Prefer semantic tokens such as `accent`, `secondary`, `content`,
  `background`, `stroke`, `success`, `warning`, and `error`.
- Prefer reusable UI components, variants, or helper functions for repeated
  utility chains.
- Use `cn`, `clsx`, `tailwind-merge`, and `cva` style composition where
  available.
- Prefer existing `tw-olm-*` plugin component classes for common OLM buttons,
  cards, inputs, labels, badges, pagination, segments, selects, tooltips,
  toggles, textareas, and menu items.
- Avoid long inline utility chains in repeated elements.
- Keep Blade classes readable; extract repeated chunks to components or partials.
- Do not hide business rules in class-name conditionals.

---

# Package Rules

- Packages should behave like reusable infrastructure or integration boundaries.
- Public package APIs should be service contracts, facades, or explicit adapters.
- Packages should not depend on internal details of a business module unless the
  dependency is documented and stable.
- Third-party credentials, request signing, response normalization, and retry
  behavior belong inside package services/adapters, not controllers.

---

# Review Checklist

Before finalizing code, check:
- Is validation in FormRequest or an equivalent request object?
- Is business logic outside controller/render/resolver code?
- Are Mongo queries centralized enough to avoid duplication?
- Is tenant isolation explicit?
- Are authorization decisions in policy/service code instead of views?
- Are types explicit enough for future maintainers?
- Are giant files left better than before, without starting a risky rewrite?
- Are hidden side effects, global helpers, and static calls avoided or isolated?
- Are tests or manual verification appropriate for the risk touched?

---

# Done Criteria

- Code is maintainable and readable.
- No new legacy pattern is introduced.
- Naming follows the owning domain.
- Types and data shapes are explicit.
- Side effects are minimal and visible.
- Cross-module dependencies are justified.
- Legacy compromises are explained when kept.
