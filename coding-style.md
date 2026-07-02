# Coding Style

This file summarizes day-to-day coding style. Detailed architecture rules live
in `AGENTS.md` and `docs/*.md`.

---

# General

- Use English for code names: files, classes, functions, variables, props,
  stores, hooks, and constants.
- Keep comments short and useful. Explain why, not what the next line already
  says.
- Prefer readable code over clever code.
- Prefer small focused modules over large files.
- Prefer explicit data shapes over loose arrays or `any`.
- Prefer documented conventions over nearby legacy code.

---

# Backend

Use the documented backend flow:

```text
Controller/Resolver -> FormRequest/Input -> Service -> Repository/Query -> Model
```

Rules:
- Keep controllers and GraphQL resolvers thin.
- Use FormRequest for validation and request normalization.
- Put business workflows in Services.
- Put complex Mongo queries and aggregations in Repository/Query classes.
- Use DTO/Object classes for complex input, filters, and operation results.
- Keep tenant scope explicit.
- Avoid new global helpers and new `Func.php` behavior.
- Use `core` primitives only for shared infrastructure.
- Put module-specific behavior in `modules/<domain>`, not in core base classes.

Naming examples:
- `ProductController`
- `ProductService`
- `ProductServiceImpl`
- `ProductRepository`
- `ProductRepositoryImpl`
- `StoreProductRequest`
- `UpdateProductRequest`
- `ProductPolicy`
- `SubmitForWorkflowInput`
- `ApproveSubmissionOutcome`

Core examples:
- `Core\Repository\BaseRepository`
- `Core\Service\BaseService`
- `Core\Object\Paginate`
- `Core\Object\PagiResult`
- `Core\GraphQL\Query`
- `Core\GraphQL\Mutation`
- `Core\RedisQueue\Contracts\QueueHandlerInterface`

---

# Frontend

Frontend source is split into:

- `resources/ts`: shared UI, hooks, utilities, types, constants, root helpers.
- `resources/modules/<domain>`: feature-specific React/TypeScript code.
- `tailwind`: OLM Tailwind tokens and component plugins.

Shared backend foundation:
- `core`: OLM framework primitives shared across modules.

Preferred feature shape:

```text
resources/modules/<domain>/
  index.tsx
  views/
  components/
  hooks/
  services/
  stores/
  types/
  utils/
  api/
  constants/
  store/
  contexts/
  styles/
```

Rules:
- Use functional components with explicit props.
- Do not require `React.FC`; use it only when it helps local consistency.
- Keep API calls in `services/`.
- Keep side effects and orchestration in `hooks/`.
- Keep reusable UI in `components/`.
- Keep shared primitives in `resources/ts/components`.
- Use `initReactRoot` for React islands that may remount.
- Check if the component already exists in /page/component; if not, create a new one.
- Type Blade bootstrap props at the React boundary.
- Avoid direct `window._api*` calls in new views/components.
- Avoid business logic inside React render.
- Avoid `useEffect` for derived state.

Component example:

```tsx
type UserCardProps = {
    name: string;
    age: number;
    onSelect: () => void;
};

export function UserCard({ name, age, onSelect }: UserCardProps) {
    return (
        <button type="button" onClick={onSelect}>
            {name} is {age} years old
        </button>
    );
}
```

Generic component example:

```tsx
type ListProps<T> = {
    items: T[];
    renderItem: (item: T) => JSX.Element;
};

export function List<T>({ items, renderItem }: ListProps<T>) {
    return <ul>{items.map(renderItem)}</ul>;
}
```

---

# React Islands

Preferred entrypoint pattern:

```tsx
const VIEW_MAP = {
    "data-react-view-example": () => import("./views/ViewExample"),
};
```

Preferred mount pattern:

```tsx
const ROOT_ID = "root-example";
const bladeProps: ViewExample = {
    ...readRootDataProps<ViewExample>(ROOT_ID)
};
mountReactComponentOnReady(ROOT_ID, <ViewExample {...bladeProps} />);
```

Avoid adding new one-off `ReactDOM.createRoot` mounts unless the island is truly
single-use and cannot remount.

---

# State

Use local state for:
- form fields,
- small dialog/menu open state,
- hover/focus state,
- temporary rendering state.

Use Zustand for:
- state shared across multiple components,
- wizard progress,
- selected rows/records used by several views,
- editor/page state that must cross component boundaries.

Rules:
- Type store state and actions.
- Prefer selectors in components.
- Use shallow selectors for grouped reads in large stores.
- Keep persisted stores small.
- Split stores that own unrelated workflows.

---

# Tailwind And UI

Rules:
- Prefer semantic tokens from `tailwind/theme/colors.js`.
- Prefer `olm-*` plugin component classes for repeated OLM UI primitives.
- Use `cn(...)` for class merging.
- Use `cva(...)` for reusable variants.
- Avoid repeated long utility chains.
- Avoid dynamic class strings Tailwind cannot discover.

Example:

```tsx
import { cn } from "@ts/components/ui/utils";

<button
    className={cn(
        "tw-olm-btn-neutral-48",
        isActive && "tw-border-accent-default tw-text-accent-default"
    )}
/>
```

---

# Legacy TypeScript Modules Without React

Some old pages still use jQuery/global TS modules. Treat them as compatibility
surfaces.

If you must touch one:
- Keep DOM selectors inside a small controls object or setup function.
- Keep initial config typed.
- Keep event binding in one method.
- Expose only the public methods required by legacy Blade/JS.
- Do not add new global behavior if a React hook/component can own it.

Minimal pattern:

```ts
type Controls = {
    submitButton: JQuery<HTMLElement>;
};

type Values = {
    endpoint: string;
};

class LegacyWidget {
    private controls!: Controls;
    private values: Values;

    constructor(values: Values) {
        this.values = values;
    }

    init(): void {
        this.controls = {
            submitButton: $("#submit-button"),
        };
        this.bindEvents();
    }

    private bindEvents(): void {
        this.controls.submitButton.on("click", () => {
            void this.submit();
        });
    }

    private async submit(): Promise<void> {
        await window._apiCreate(this.values.endpoint, {});
    }
}
```

Prefer moving new behavior into typed services/hooks when possible.
