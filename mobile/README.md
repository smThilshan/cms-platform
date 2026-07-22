# CMS Mobile

Flutter read-only client for the CMS Platform. Displays published pages sourced from the public API — no login required.

## Stack

| Concern | Package |
|---|---|
| HTTP | `dio` |
| State management | `flutter_bloc` (Cubit pattern) |
| Navigation | `go_router` |
| HTML rendering | `flutter_html` |
| Image caching | `cached_network_image` |
| Env config | `flutter_dotenv` |

## Prerequisites

- Flutter 3.x (`flutter --version` to check)
- iOS Simulator or Android Emulator running
- CMS backend running (see root `README.md` for setup)

## Setup

```bash
cp .env.example .env
```

Edit `.env` and set the correct API URL for your platform:

```dotenv
# iOS Simulator
API_BASE_URL=http://localhost:8000/api

# Android Emulator
API_BASE_URL=http://10.0.2.2:8000/api

# Physical device (use your Mac's LAN IP)
API_BASE_URL=http://192.168.x.x:8000/api
```

Install dependencies and run:

```bash
flutter pub get
flutter run
```

## Screens

| Screen | Route | Description |
|---|---|---|
| Home | `/` | Card list of all published pages (sourced from menu) |
| Page Detail | `/page/:slug` | Cover image + title + rendered HTML body |

## Architecture

Feature-based folder structure with BLoC (Cubit) for state:

```
lib/
├── core/
│   ├── api/          Single Dio instance, base URL from .env
│   ├── router/       go_router — push for detail, go for top-level
│   └── theme/        Centralised colours and Material 3 theme
└── features/
    ├── home/
    │   ├── data/     PageModel + MenuItemModel + PageRepository
    │   ├── cubit/    HomeCubit — sealed states (Initial/Loading/Loaded/Error)
    │   └── ui/       HomeScreen + PageCard widget
    └── page_detail/
        ├── cubit/    PageDetailCubit — route-scoped (fresh per navigation)
        └── ui/       PageDetailScreen
```

## Notes

- `PageDetailCubit` is scoped per route, not app-level — no stale content flashes when navigating between pages.
- The public API exposes pages through menu items (`GET /api/menu`). The repository flattens nested menu items into a flat page list.
- `.env` is git-ignored. Never commit real API URLs or credentials.
