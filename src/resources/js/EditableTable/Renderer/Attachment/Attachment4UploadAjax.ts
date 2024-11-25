import { TableConfig } from '../../Type/EditableTable3ConfigType'
import { TableDataLine } from '../../Type/EditableTable3DataLineType'

export const attachment4UploadFileAjax = async (
    tableConfig: TableConfig,
    files: FileList | null,
    fieldName: string,
    groupId: number | null,
    dataLine: TableDataLine,
) => {
    if (!files || files.length === 0) return

    const formData = new FormData()
    formData.append('object_type', tableConfig.lineObjectModelPath || 'no-objectType')
    formData.append('object_id', (dataLine['id'] as unknown as string) || 'no-objectId')

    // Add files to FormData
    for (let i = 0; i < files.length; i++) {
        formData.append(`${fieldName}[toBeUploaded][${groupId}][${i}]`, files[i])
    }

    try {
        const url = tableConfig.uploadServiceEndpoint || 'no-endpoint'

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        if (!csrfToken) {
            throw new Error('CSRF token not found. Ensure the meta tag is present.')
        }

        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken, // Attach the CSRF token
            },
        })

        if (response.ok) {
            const result = await response.json()
            // console.log('Upload successful:', result)
            return result
        } else {
            console.error('Upload failed:', response.statusText)
        }
    } catch (error) {
        console.error('Error during upload:', error)
    }
}
