# OLM Laravel — Claude Quick Reference

Primary source of truth: **AGENTS.md** and **docs/*.md**.
This file adds practical shortcuts, module-specific context, and non-obvious
gotchas on top of those docs.

---

## Stack at a Glance

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8.5.0+, MongoDB (Jenssegers) |
| Multi-tenant | stancl/tenancy |
| API | Rebing GraphQL + REST |
| Frontend | React 18, TypeScript, Tailwind CSS (`tw-` prefix) |
| State | Zustand (shared), React local state (local) |
| Editor | Lexical Editor |
| UI | Radix UI, `tw-olm-*` plugin classes |

PHP 7.3/7.4 — no native enums, no match expressions, no union types.
Use typed constant classes and value objects (see `docs/backend-conventions.md`).

---

## Project Layout

```
app/           Laravel shell (providers, middleware, base controllers, commands)
core/          OLM framework primitives (base model/repo/service, GraphQL base,
               pagination objects, Redis queue, Blade components, facades)
modules/       Business domains — most feature work belongs here
packages/      Infrastructure adapters (upload, firebase, openai, zalo, rabbitmq…)
resources/ts/  Shared frontend (components, hooks, utils, types, stores)
resources/modules/  Frontend feature modules (aligned with backend modules)
tailwind/      OLM design tokens, CSS variables, tw-olm-* plugins
docs/          Convention docs — read before coding
```

---

## Backend Flow (approved)

```
Route → FormRequest → Controller → Service/Impl → Repository/Impl → Model
                                 → DTO/Object → Response
```

GraphQL:
```
Input/Filter → Resolver → Service/Impl → Repository/Impl → Model → Type
```

**Controller**: thin — inject service, call FormRequest, return response.
**FormRequest**: validation rules, authorization check, `toFilter()` / `toPaginate()` helpers.
**Service**: business workflow, transactions, side effects. Receives DTOs/IDs, never raw `Request`.
**Repository**: Mongo queries, aggregation pipelines, pagination. Interface in `Repository/`, impl in `Repository/Impl/`.
**Model**: persistence mapping, constants, casts. No business logic.

---

## Adding a Feature to an Existing Module

1. **FormRequest** in `modules/<domain>/Request/Frm<Action>.php`
2. **Service interface** in `modules/<domain>/Service/<Name>Service.php`
3. **Service impl** in `modules/<domain>/Service/Impl/<Name>ServiceImpl.php`
4. **Repository interface** in `modules/<domain>/Repository/<Name>Repository.php`
5. **Repository impl** in `modules/<domain>/Repository/Impl/<Name>RepositoryImpl.php`
6. **DTO/Object** in `modules/<domain>/Object/` if needed
7. **Policy** in `modules/<domain>/Policy/` + register Gate in `Provider.php`
8. **Controller method** (or new controller) in `modules/<domain>/Controller/`
9. **Route** in `modules/<domain>/routes.php`
10. **Bind** interface → impl as singleton in `modules/<domain>/Provider.php`

Never add module business logic to `core/`, `core/Func.php`, `core/Helper.php`,
`Core\Service\BaseService`, or `Core\Repository\BaseRepository`.

---

## Typed Constants (PHP 8.5 — no native enum)

```php
// modules/<domain>/Object/ExampleStatus.php
final class ExampleStatus
{
    public const DRAFT    = 'draft';
    public const ACTIVE   = 'active';
    public const ARCHIVED = 'archived';

    public static function all(): array
    {
        return [self::DRAFT, self::ACTIVE, self::ARCHIVED];
    }

    private function __construct() {}
}
```

Use `in_array($value, ExampleStatus::all(), true)` in FormRequest validation.
Never compare raw string literals to status fields in services — always use the constant.



## Frontend Flow (approved)

```
Blade JSON script → index.tsx VIEW_MAP → initReactRoot → view component
                 → hooks/stores/services → focused UI components
```

Feature entrypoint pattern:


View mount pattern:

```tsx
// resources/modules/<domain>/views/ViewExample.tsx
const ROOT_ID = "root-example";
const bladeProps: ViewExample = {
    ...readRootDataProps<ViewExample>(ROOT_ID)
};
mountReactComponentOnReady(ROOT_ID, <ViewExample {...bladeProps} />);
```

Never use `ReactDOM.createRoot` directly in new code.
Never call `window._api*` directly from views/components — wrap in `services/`.

---

## Tailwind / Styling

- Semantic tokens: `accent`, `secondary`, `content`, `background`, `stroke`,
  `success`, `warning`, `error`
- Plugin component classes: `olm-btn-*`, `olm-card-*`, `olm-input-*`, etc.
- Class composition: `cn()`, `clsx`, `tailwind-merge`, `cva`
- Avoid long raw utility chains — extract to a component, `cva` variant, or
  `olm-*` class

---

## Packages Available (`packages/`)

| Package | Purpose |
|---------|---------|
| phpExcel | class reading file excel|
Use package service contracts from modules — do not reach into package internals.

---

## Common Gotchas

- **MongoDB ObjectId**: convert explicitly at boundaries; do not rely on implicit casting.
- **Soft deletes**: check for `deleted_at` / `status` filter in queries — many collections use a status field rather than Laravel soft-delete.
- **Mobile views**: `Provider.php` loads views from `View/desktop` or `View/mobile` via `isMobile()`. Keep both in sync when changing Blade.
- **Legacy `Func.php`**: do not add new logic; wrap or isolate when touching.
- **Cross-module data**: access other module data through its service contract, not its repository or model directly.
- **Core base changes**: high blast radius — inspect all consumers before changing `BaseRepository`, `BaseService`, or `BaseModel`.
- **School stats**: aggregation pipelines live in `SchoolRepository` and `SchoolServiceImpl`; do not duplicate.

---

## Review Checklist (before finalizing)

- [ ] Validation in FormRequest, not controller
- [ ] Business logic in Service, not controller or Blade
- [ ] Mongo queries in Repository, not scattered
- [ ] Tenant scope explicit in every query/cache/queue
- [ ] Authorization in Policy / Gate, not hidden in Blade or model accessor
- [ ] No raw string literals for status/type fields — use typed constant class
- [ ] New service/repository bound in `Provider.php`
- [ ] No new code added to `core/Func.php` or base classes
- [ ] Frontend: API in `services/`, orchestration in `hooks/`, mount via `initReactRoot`
- [ ] Tailwind uses `tw-` prefix and semantic tokens
