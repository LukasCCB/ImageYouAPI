# ImageYouAPI

ImageYouAPI is a simple and efficient REST API built with Laravel 12 for managing image uploads. It allows users to upload, view, and delete images seamlessly. The API organizes images into folders based on a provided "Reference" name and assigns unique filenames using UUIDs to ensure no conflicts.

## Features

- **Upload Images**: Upload images with a specified reference folder and format.
- **View Images**: Retrieve uploaded images by their unique identifier.
- **Delete Images**: Remove images from the server using their unique identifier.

---

## API Endpoints

### 1. Upload an Image
**Endpoint**: `POST /api/v1/upload`

**Request Body**:
- `image` (file): The image file to upload.
- `reference` (text): The folder name where the image will be stored.
- `format` (text): The desired image format (e.g., `jpeg`, `png`, `gif`, `webp`, `avif`).

**Example Request**:
```http
POST /api/v1/upload
Accept: application/json

{
    "image": "example.jpg",
    "reference": "melao",
    "format": "webp"
}
```

**Example Response**:
```json
{
  "id": "8acd1e2f-1308-4be5-bf45-c798a073cd13",
  "url": "http://imageyouapi.test/images/melao/8acd1e2f-1308-4be5-bf45-c798a073cd13.webp"
}
```

### 2. View an Image
**Endpoint**: `POST /api/v1/image/{hash}`

**Request Param**:
- `hash` (UUID): The unique identifier of the image.

**Example Request**:
```http
GET /api/v1/image/cd64bb7a-ffb4-467c-a00e-8acd8feb7734
Accept: application/json
```

**Example Response**:
```json
{
    "id": "cd64bb7a-ffb4-467c-a00e-8acd8feb7734",
    "url": "http://imageyouapi.test/images/melao/cd64bb7a-ffb4-467c-a00e-8acd8feb7734.webp"
}
```

### 3. Delete an Image
**Endpoint**: `DELETE /api/v1/image/{hash}`

**Request Param**:
- `hash` (UUID): The unique identifier of the image.

**Example Request**:
```http
DELETE /api/v1/image/cd64bb7a-ffb4-467c-a00e-8acd8feb7734
Accept: application/json
```

**Example Response**:
```json
{
    "id": "cd64bb7a-ffb4-467c-a00e-8acd8feb7734",
    "url": "Image deleted successfully"
}
```
