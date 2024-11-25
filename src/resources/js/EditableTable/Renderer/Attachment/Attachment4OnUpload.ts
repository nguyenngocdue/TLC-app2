import { TableConfig } from '../../Type/EditableTable3ConfigType'
import { DataSourceItem, TableDataLine } from '../../Type/EditableTable3DataLineType'
import { Thumbnail4View } from '../Thumbnail/Thumbnail4View'
import { attachment4UploadFileAjax } from './Attachment4UploadAjax'

export const addOnUploadListener = (
    inputElement: HTMLInputElement,
    controlId: string,
    tableConfig: TableConfig,
    fieldName: string,
    groupId: number | null,
    dataLine: TableDataLine,
) => {
    inputElement.addEventListener('change', async (e) => {
        const files = (e.target as HTMLInputElement).files
        // console.log(fieldName, groupId)
        if (files) {
            for (let i = 0; i < files.length; i++) {
                const imgContainerId = `${controlId}__${i}__img_container`
                const emptyBox = Thumbnail4View.renderThumbnailBox(`${controlId}__${i}`).outerHTML
                const div = `<div id="${imgContainerId}">${emptyBox}</div>`
                $(`#${controlId}_thumbnail_div`).append(div)
                const onProgress = (percent: number) => {
                    const id = `#${controlId}__${i}__progress_bar`
                    $(id).css('width', `${percent}%`)
                    // console.log(id, percent)
                    // console.log('Upload progress:', file.name, percent)
                }
                const onDone = (result: any) => {
                    const id = document.querySelector(`#${imgContainerId}`) as Element
                    const obj: DataSourceItem = Object.values(
                        result,
                    )[0] as unknown as DataSourceItem

                    const item = {
                        src: 'https://minio.tlcmodular.com/tlc-app/' + obj.url_thumbnail,
                        name: obj.filename,
                    }

                    if (id && id.parentElement) {
                        id.parentElement.append(Thumbnail4View.renderThumbnailBox(item))
                        //remove the img_container
                        $(`#${imgContainerId}`).remove()
                    }
                }

                try {
                    attachment4UploadFileAjax(
                        tableConfig,
                        files[i],
                        fieldName,
                        groupId,
                        dataLine,
                        onProgress,
                    ).then((result) => {
                        onDone(result)
                    })
                } catch (e) {
                    console.error('Upload error:', e)
                }
            }
        }
    })
}
