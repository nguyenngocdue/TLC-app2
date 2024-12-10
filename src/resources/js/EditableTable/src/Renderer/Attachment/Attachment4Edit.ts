import { TableColumnAttachment } from '../../Type/EditableTable3ColumnType'
import { Renderer4Edit } from '../Renderer4Edit'
import { Thumbnail4View } from '../Thumbnail/Thumbnail4View'
import { addOnUploadListener } from './Attachment4OnUpload'

export class Attachment4Edit extends Renderer4Edit {
    protected tdClass = 'overflow-x-auto'
    protected divClass = 'overflow-auto'

    private reRenderThumbnail() {
        const params = this.getTableRendererParams()
        const thumbnailColumn = params.column as TableColumnAttachment
        if (thumbnailColumn.rendererAttrs) {
            // thumbnailColumn.rendererAttrs.maxToShow = Number.MAX_VALUE
            // thumbnailColumn.rendererAttrs.maxPerLine = 5
        }
        const thumbnailDiv = new Thumbnail4View(params).render().rendered
        $(`#${this.controlId}_thumbnail_div_view`).html(thumbnailDiv)
        // document.getElementById(`${this.controlId}_thumbnail_div_view`).innerHTML = thumbnailDiv
    }

    applyPostRenderScript(): void {
        // console.log('Attachment4Edit.applyPostScript()')
        this.reRenderThumbnail()
    }

    control() {
        const column = this.column as TableColumnAttachment
        const { controlId, tableConfig } = this

        const {
            fileType = 'image',
            maxFileCount = 1,
            // maxFileSize = 1024,
            uploadable = true,
            // deletable = true,
            fieldName = column.dataIndex.toString(),
            groupId = null,
        } = column.rendererAttrs || {}

        const classList = `border rounded bg-gray-100 hover:bg-gray-200 p-1 w-full cursor-pointer`

        // Create input element dynamically
        const inputId = `${controlId}_file_input`
        const inputElement = document.createElement('input')
        inputElement.id = inputId
        // inputElement.name = controlName
        inputElement.type = 'file'
        inputElement.className = classList
        inputElement.accept = `${fileType}/*`
        inputElement.style.display = 'none'
        if (maxFileCount > 1) inputElement.multiple = true
        addOnUploadListener(inputElement, controlId, tableConfig, fieldName, groupId, this.dataLine)
        document.body.appendChild(inputElement)

        // Generate upload button HTML
        const uploadButton = !uploadable
            ? ''
            : `<button 
            class="text-xs border rounded bg-blue-500 hover:bg-blue-700 text-white px-2 py-1" 
            type="button" 
            onclick="document.getElementById('${inputId}').click()"
        ><i class="fa fa-upload"></i></button>`

        return `<div class="flex items-center gap-0.5">
        <div id="${controlId}_thumbnail_div_view"></div>
        ${uploadButton}
        </div>`
    }
}
