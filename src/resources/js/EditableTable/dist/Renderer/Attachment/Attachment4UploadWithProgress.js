"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.uploadFileWithProgress = void 0;
const uploadFileWithProgress = (url, 
// file: File,
formData, onProgress) => {
    return new Promise((resolve, reject) => {
        var _a;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        // Attach CSRF token (if needed)
        const csrfToken = (_a = document.querySelector('meta[name="csrf-token"]')) === null || _a === void 0 ? void 0 : _a.getAttribute('content');
        if (csrfToken) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        }
        // Track upload progress for this file
        xhr.upload.onprogress = (event) => {
            if (event.lengthComputable) {
                const percentComplete = Math.round((event.loaded / event.total) * 100);
                onProgress(percentComplete);
            }
        };
        // Handle completion
        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 300) {
                const jsonResponse = JSON.parse(xhr.responseText);
                resolve(jsonResponse);
            }
            else {
                reject(new Error(`Upload failed: ${xhr.statusText}`));
            }
        };
        // Handle errors
        xhr.onerror = () => {
            reject(new Error('Error during upload'));
        };
        // Send the form data
        xhr.send(formData);
    });
};
exports.uploadFileWithProgress = uploadFileWithProgress;
//# sourceMappingURL=Attachment4UploadWithProgress.js.map