# Profile Picture Persistence — Base64 Database Storage

## Problem

Platforms like Railway, Render, and Heroku use **ephemeral storage** — any files written to disk (e.g. `storage/app/public/profile_images/`) are deleted when the container restarts or redeploys. This caused profile pictures to disappear after every deployment.

## Solution

Profile pictures are now converted to **base64 data URIs** and stored directly in the `users` table (`profile_picture_base64` column, `LONGTEXT`). Since the database persists across deployments, images are never lost.

The original `profile_photo_path` column is kept for backward compatibility.

## How It Works

### Upload Flow
1. User uploads an image (jpeg/png/jpg, max 2MB) on the profile page.
2. `AuthController::updateProfile` reads the file, encodes it to base64, and prepends the data URI scheme:
   ```
   data:image/jpeg;base64,/9j/4AAQSkZJRgAB...
   ```
3. The full string is saved to `users.profile_picture_base64`.
4. The file is also stored to disk (for local dev convenience) in `profile_photo_path`.

### Display Priority (all views)
```blade
{{ $user->profile_picture_base64
    ?? ($user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('ASSETS/blank-pfp.png')) }}
```
1. **Base64** from DB — always works in production
2. **File path** — fallback for existing users before migration
3. **Placeholder** (`blank-pfp.png`) — shown when no image is set

## Files Changed

| File | Change |
|------|--------|
| `database/migrations/2026_04_25_000000_add_profile_picture_base64_to_users_table.php` | Adds `profile_picture_base64 LONGTEXT NULL` column |
| `app/Models/User.php` | Added `profile_picture_base64` to `$fillable` |
| `app/Http/Controllers/AuthController.php` | Converts upload to base64 data URI on save |
| `resources/views/profile.blade.php` | Uses base64 → file path → placeholder |
| `resources/views/dashboard.blade.php` | Uses base64 → file path → placeholder |
| `resources/views/notes.blade.php` | Uses base64 → file path → placeholder |
| `resources/views/user.blade.php` | Uses base64 → file path → placeholder |

## Deployment Steps

### First-time setup (local or production)

```bash
php artisan migrate
```

That's it. The migration adds the new column without touching existing data.

### Railway / Render / Heroku

1. Push your code.
2. The platform runs `php artisan migrate` automatically (if configured), or run it manually via the platform's shell/console.
3. Users who re-upload their profile picture will have it stored in the DB and persist forever.

### Existing users

Existing users with a `profile_photo_path` will still see their picture (fallback logic). Once they re-upload, the base64 version takes over.

## Validation Rules

```php
'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
```

- Accepted formats: `jpeg`, `png`, `jpg`
- Max size: **2 MB**
- Field is optional — skipping upload keeps the existing picture

## Storage Size Consideration

Base64 encoding increases image size by ~33%. A 2 MB image becomes ~2.7 MB in the DB. For a small-to-medium user base this is perfectly fine with MySQL/PostgreSQL `LONGTEXT` (supports up to 4 GB per cell).

If you expect thousands of users with large avatars, consider using an object storage service like **Amazon S3** or **Cloudflare R2** instead.

## No External Dependencies

This solution requires:
- No API keys
- No third-party packages
- No cloud storage configuration
- Works identically on local XAMPP and any cloud platform
