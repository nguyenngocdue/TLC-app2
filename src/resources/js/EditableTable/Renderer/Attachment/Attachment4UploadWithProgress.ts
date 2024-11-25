export const uploadFileWithProgress = (
    url: string,
    file: File,
    formData: FormData,
    onProgress: (file: File, percent: number) => void,
) => {
    return new Promise<void>((resolve, reject) => {
        const xhr = new XMLHttpRequest()

        xhr.open('POST', url, true)

        // Attach CSRF token (if needed)
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        if (csrfToken) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken)
        }

        // Track upload progress for this file
        xhr.upload.onprogress = (event) => {
            if (event.lengthComputable) {
                const percentComplete = Math.round((event.loaded / event.total) * 100)
                onProgress(file, percentComplete)
            }
        }

        // Handle completion
        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 300) {
                const jsonResponse = JSON.parse(xhr.responseText)
                resolve(jsonResponse)
            } else {
                reject(new Error(`Upload failed: ${xhr.statusText}`))
            }
        }

        // Handle errors
        xhr.onerror = () => {
            reject(new Error('Error during upload'))
        }

        // Send the form data
        xhr.send(formData)
    })
}
