import { TableConfig } from '../../Type/EditableTable3ConfigType'
import { TableDataLine } from '../../Type/EditableTable3DataLineType'
import { uploadFileWithProgress } from './Attachment4UploadWithProgress'

export const attachment4UploadFileAjax = async (
    tableConfig: TableConfig,
    file: File | null,
    fieldName: string,
    groupId: number | null,
    dataLine: TableDataLine,
    onProgress: (percent: number) => void,
) => {
    if (!file) return

    const formData = new FormData()
    // const envConfig = tableConfig.envConfig || {}
    formData.append('object_type', tableConfig.entityLineType || 'no-entityLineType')
    formData.append('object_id', (dataLine['id'] as unknown as string) || 'no-objectId')
    formData.append(`${fieldName}[toBeUploaded][${groupId}][]`, file)

    const url = tableConfig.uploadServiceEndpoint || 'no-endpoint'
    return uploadFileWithProgress(url, formData, onProgress)
}
