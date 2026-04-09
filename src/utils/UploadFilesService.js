import {$toast} from "./toast";

export class UploadFilesService {
    static allowed_data_types = {
        ALL_IMG: {mime: "image/*", ext: ".*"},
        JPEG: {mime: "image/jpeg", ext: "jpeg"},
        PNG: {mime: "image/png", ext: "jpg"},
        GIF: {mime: "image/gif", ext: "gif"},
        TIFF: {mime: "image/tiff", ext: "tiff"},
        SVG: {mime: "image/svg+xml", ext: "svg"},
        BMP: {mime: "image/bmp", ext: "bmp"},
        WEBP: {mime: "image/webp", ext: "webp"},
        PDF: {mime: "application/pdf", ext: "pdf"},
        JSON: {mime: "application/json", ext: "json"},
        ZIP: {mime: "application/zip", ext: "zip"},
        TEXT: {mime: "text/plain", ext: "txt"},
        HTML: {mime: "text/html", ext: "html"},
        CSS: {mime: "text/css", ext: "css"},
        AUDIO_MPEG: {mime: "audio/mpeg", ext: "mpeg"},
        AUDIO_WAV: {mime: "audio/wav", ext: "wav"},
        AUDIO_OGG: {mime: "audio/ogg", ext: "ogg"},
        VIDEO_MP4: {mime: "video/mp4", ext: "mp4"},
        VIDEO_WEBM: {mime: "video/webm", ext: "webm"},
        VIDEO_OGG: {mime: "video/ogg", ext: "ogg"},
        XLSX: {
            mime: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            ext: "xlsx",
        },
        CSV: {mime: "text/csv" || "application/csv", ext: "csv"},
    };

    static max_upload_size = 10485760; //10 MB to bytes = 10485760

    /**
     * @param {File} file
     * @param {string[]} allowed_data_types
     * @param {number} max_size
     */
    static validateUploadedFile(
        file,
        allowed_data_types,
        max_size = UploadFilesService.max_upload_size
    ) {
        if (file.size > max_size) {
            $toast.warn(
                'El archivo: "' +
                    file.name +
                    '" excede el limite permitido (10MB).'
            );
            throw new Error("file exced size limit.");
        }
        if (
            allowed_data_types?.length > 0 &&
            !allowed_data_types.includes(file.type)
        ) {
            $toast.warn(
                'El archivo: "' + file.name + '" es de un tipo no permitido.'
            );
            throw new Error("File type not allowed");
        }
        return true;
    }
}
