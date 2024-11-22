import { TableColumnAttachment } from '../../Type/EditableTable3ColumnType'
import { TableConfig } from '../../Type/EditableTable3ConfigType'
import { Renderer4Edit } from '../Renderer4Edit'

export class Attachment4Edit extends Renderer4Edit {
    // Upload file method
    async uploadFile(
        tableConfig: TableConfig,
        files: FileList | null,
        fieldName: string,
        groupId: number,
    ) {
        if (!files || files.length === 0) return

        const formData = new FormData()
        formData.append('object_type', tableConfig.lineObjectModelPath || 'no-objectType')
        formData.append('object_id', (this.dataLine['id'] as unknown as string) || 'no-objectId')

        // Add files to FormData
        for (let i = 0; i < files.length; i++) {
            formData.append(`${fieldName}[toBeUploaded][${groupId}][]`, files[i])
        }

        try {
            const url = this.tableConfig.uploadServiceEndpoint || 'no-endpoint'

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content')
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
                console.log('Upload successful:', result)
            } else {
                console.error('Upload failed:', response.statusText)
            }
        } catch (error) {
            console.error('Error during upload:', error)
        }
    }

    control() {
        const column = this.column as TableColumnAttachment
        const { controlId, controlName, tableConfig } = this

        const {
            fileType = 'image',
            maxFileCount = 1,
            maxFileSize = 1024,
            uploadable = true,
            showUploader = true,
            showUploadDate = true,
            deletable = true,
            fieldName = column.dataIndex,
            groupId = 0,
        } = column.rendererAttrs || {}

        const classList = `border rounded bg-gray-100 hover:bg-gray-200 p-1 w-full cursor-pointer`

        // Create input element dynamically
        const inputId = `${controlId}_file_input`
        const inputElement = document.createElement('input')
        inputElement.id = inputId
        inputElement.name = controlName
        inputElement.type = 'file'
        inputElement.className = classList
        inputElement.accept = `${fileType}/*`
        inputElement.style.display = 'none'
        if (maxFileCount > 1) {
            inputElement.multiple = true
        }
        inputElement.addEventListener('change', (e) => {
            const files = (e.target as HTMLInputElement).files
            this.uploadFile(tableConfig, files, fieldName, groupId)
        })

        // Add input element to DOM
        document.body.appendChild(inputElement)

        // Generate upload button HTML
        const uploadButton = `<button 
            class="btn btn-primary" 
            type="button" 
            onclick="document.getElementById('${inputId}').click()"
        >Upload</button>`

        return `${uploadButton}`
    }
}
