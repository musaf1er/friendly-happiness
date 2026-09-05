# Asset Structure

Static design assets live under `assets/images/`. Runtime files uploaded from the admin panel live under `uploads/` and should not be mixed with source imagery.

## Branding

Path: `assets/images/branding/`

Use this for the club mark, navigation logo, and favicon source. The current UI uses the text mark `MO` in `includes/header.php`; add files here when replacing it with real branding, such as `logo-main.webp`, `logo-mark.webp`, and `favicon.png`.

## Homepage and clubhouse

- `assets/images/hero/`: homepage hero photography. Recommended 1920 x 1080 or wider, 16:9, WebP preferred.
- `assets/images/clubhouse/`: clubhouse and meeting-space photography.

The current homepage hero uses `assets/images/hero/hero-clubhouse-demo.jpg`; the garage band uses `assets/images/clubhouse/clubhouse-garage-demo.jpg`.

## Demo Images

Files ending in `-demo.jpg` are placeholder assets and may be replaced in place without changing PHP. The downloaded demo set is local and does not create a runtime dependency on Unsplash.

- `assets/images/hero/hero-clubhouse-demo.jpg`: homepage hero. Replace with a real 1920 x 1080 or wider 16:9 WebP/JPEG photograph.
- `assets/images/clubhouse/clubhouse-exterior-demo.jpg`, `clubhouse-garage-demo.jpg`, `clubhouse-bikes-demo.jpg`: clubhouse and garage reference imagery.
- `assets/images/gallery/touring-01.jpg` through `touring-03.jpg`: touring gallery samples.
- `assets/images/gallery/clubhouse-01.jpg` and `clubhouse-02.jpg`: workshop/clubhouse samples.
- `assets/images/gallery/bike-01.jpg` through `bike-03.jpg`: motorcycle detail samples.
- `assets/images/gallery/event-gallery-01.jpg` and `event-gallery-02.jpg`: event meet samples.
- `assets/images/events/night-ride-demo.jpg`, `club-meet-demo.jpg`, `garage-night-demo.jpg`, `weekend-ride-demo.jpg`: event imagery. Event seed rows currently use the first and third files.
- `assets/images/officers/officer-president-demo.jpg`, `officer-road-captain-demo.jpg`, `officer-saa-demo.jpg`: subdued 4:5 demo portraits. They are not real club identities.
- `assets/images/merchandise/shirt-demo.jpg`, `hoodie-demo.jpg`, `patch-demo.jpg`, `sticker-demo.jpg`: product samples.
- `assets/images/placeholders/default.jpg`: neutral local fallback used when a database image path is empty.

Recommended replacement dimensions remain 1600px maximum long edge for gallery images, 1200 x 1500 for officers, 1200 x 1200 for merchandise, and 1600 x 900 for events.

## Content imagery

- `assets/images/gallery/`: static gallery seed/demo images. Recommended maximum long edge 1600px, WebP or JPEG with reasonable compression.
- `assets/images/events/`: static/default event imagery. Recommended 1600 x 900, 16:9, WebP or JPEG.
- `assets/images/officers/`: default officer portraits. Recommended 1200 x 1500, 4:5 portrait, WebP preferred.
- `assets/images/merchandise/`: default product images. Recommended 1200 x 1200, square, WebP preferred.
- `assets/images/placeholders/`: missing-image fallbacks. The current fallback is `default.jpg`.

Database image fields in the demo seed use local paths. Local paths are served relative to the project root and pass through `image_url()` in `includes/functions.php`.

## Runtime uploads

Images uploaded through the admin panel are generated with random names and stored automatically in:

- `uploads/gallery/`
- `uploads/events/`
- `uploads/officers/`
- `uploads/merchandise/`

Do not manually place admin-uploaded files there except for debugging. The upload helper validates MIME type, extension mapping, size, and upload errors. The current admin form exposes direct upload for gallery records; officer and merchandise handlers also support the categorized upload fields when present.

## Replacing images

1. Put the club logo in `assets/images/branding/`.
2. Put homepage photography in `assets/images/hero/`.
3. Put static garage images in `assets/images/gallery/`.
4. Put officer portraits in `assets/images/officers/`.
5. Put merchandise images in `assets/images/merchandise/`.
6. Put event imagery in `assets/images/events/`.
7. Use the admin panel for runtime uploads; do not mix those files into `assets/images/`.
8. Prefer WebP, with JPEG as a compatible fallback, and follow the dimensions above.

See `README.md` for local setup and the Tailwind build commands.
