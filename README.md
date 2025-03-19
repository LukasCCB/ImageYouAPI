# ImageYouAPI

ImageYouAPI is a simple and efficient REST API built with Laravel 12 for managing image uploads. It allows users to upload, view, and delete images seamlessly. The API organizes images into folders based on a provided "Reference" name and assigns unique filenames using UUIDs to ensure no conflicts.

## Features

- **Upload Images**: Upload images with a specified reference folder and format.
- **View Images**: Retrieve uploaded images by their unique identifier.
- **Delete Images**: Remove images from the server using their unique identifier.

---

## API Endpoints

### 1. Upload Image(s)
**Endpoint**: `POST /api/v1/upload`

**Request Body**:
- `type` (image \ video): If upload file is a video or image.
- `image` (file): The image file to upload. Its array.
- `reference` (text): The folder name where the image will be stored.
- `format` (text): The desired image format (e.g., `jpeg`, `png`, `gif`, `webp`, `avif`).

**Example Request**:
```http
POST /api/v1/upload
Accept: multipart/form-data

type: image
image[]: example.jpg
image[]: other_image.jpg
image[]: three_image.jpg
reference: melao
format: webp
```

**Example Response**:
```json
{
    "message": "Upload iniciado, imagens serão processadas",
    "images": [
        {
            "id": "b832a635-2eb4-46c5-9a53-dc975dd5713d",
            "url": "http://imageyouapi.test/storage/images/melao/b832a635-2eb4-46c5-9a53-dc975dd5713d.webp"
        },
        {
            "id": "de4e3866-c9ec-4cda-b1f6-8085834d4a29",
            "url": "http://imageyouapi.test/storage/images/melao/de4e3866-c9ec-4cda-b1f6-8085834d4a29.webp"
        }
    ]
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
