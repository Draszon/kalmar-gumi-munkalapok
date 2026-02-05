# Copilot Instructions - KalmárGumi Munkalapok

## Project Overview
A tire shop worksheet management system (Hungarian: "munkalapok") built with **Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS**. Tracks vehicle services, used materials, and tire storage for KalmárGumi.

## Architecture

### Stack
- **Backend**: Laravel 12, PHP 8.2+, Inertia.js server adapter
- **Frontend**: Vue 3 (Composition API with `<script setup>`), Inertia.js client
- **Styling**: Tailwind CSS 3.4 with `@tailwindcss/forms` and `@tailwindcss/typography`
- **Auth**: Laravel Jetstream + Sanctum (currently disabled in routes)

### Data Flow
```
Vue Page → useForm() POST → Laravel Controller → Eloquent Model → Database
         ← Inertia::render() ← props (services, materials) ←
```

### Core Domain Models
- `WorkSheet` - Main entity: stores vehicle info, selected services/materials as JSON arrays
- `Service` - Lookup table for available services (e.g., tire mounting)
- `UsedMaterial` - Lookup table for materials (e.g., valve stems)

## Developer Commands

```bash
# Full dev environment (server + queue + logs + vite in parallel)
composer dev

# Initial setup
composer setup

# Run tests
composer test
```

## Code Conventions

### Vue Components
- Use Composition API with `<script setup>` syntax exclusively
- Import path alias: `@/` maps to `resources/js/`
- Form handling: Always use `useForm()` from `@inertiajs/vue3`
- Flash messages: Access via `usePage().props.flash.message`

```vue
// Example pattern from Work.vue
const workForm = useForm({
  registration_number: '',
  services: [],  // Array for checkbox groups
});
workForm.post('/store-worksheet', { preserveScroll: true });
```

### Controllers
- Return Inertia responses: `Inertia::render('PageName', ['prop' => $data])`
- Validation in controller methods, not Form Requests
- Flash messages via `redirect()->back()->with('message', '...')`

### Models
- JSON columns cast to arrays: `protected $casts = ['services' => 'array']`
- Use `$fillable` for mass assignment protection

### Styling
- Max content width: `w-[1200px]` centered container
- Primary accent: `green-600` for focus states and interactive elements
- Form inputs: Use `InputField` component or match its styling pattern
- Checkboxes: `rounded-full` style with `text-green-600`

## File Structure Patterns

```
resources/js/
├── Pages/          # Inertia page components (Work.vue, Dashboard.vue)
├── Layouts/        # MainLayout.vue (public), AppLayout.vue (authenticated)
├── Components/     # Reusable: InputField.vue, MenuBtn.vue, etc.
```

## Hungarian Language Context
UI text is in Hungarian. Key terms:
- Munkalap = Worksheet
- Rendszám = License plate
- Gumiabroncs = Tire
- Tárolás = Storage
- Szolgáltatás = Service
