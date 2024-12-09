"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Attachment4Edit = void 0;
const Renderer4Edit_1 = require("../Renderer4Edit");
const Thumbnail4View_1 = require("../Thumbnail/Thumbnail4View");
const Attachment4OnUpload_1 = require("./Attachment4OnUpload");
class Attachment4Edit extends Renderer4Edit_1.Renderer4Edit {
    constructor() {
        super(...arguments);
        this.tdClass = 'overflow-x-auto';
        this.divClass = 'overflow-auto';
    }
    reRenderThumbnail() {
        const params = this.getTableRendererParams();
        const thumbnailColumn = params.column;
        if (thumbnailColumn.rendererAttrs) {
            // thumbnailColumn.rendererAttrs.maxToShow = Number.MAX_VALUE
            // thumbnailColumn.rendererAttrs.maxPerLine = 5
        }
        const thumbnailDiv = new Thumbnail4View_1.Thumbnail4View(params).render().rendered;
        $(`#${this.controlId}_thumbnail_div_view`).html(thumbnailDiv);
    }
    applyPostRenderScript() {
        // console.log('Attachment4Edit.applyPostScript()')
        this.reRenderThumbnail();
    }
    control() {
        const column = this.column;
        const { controlId, tableConfig } = this;
        const { fileType = 'image', maxFileCount = 1, 
        // maxFileSize = 1024,
        uploadable = true, 
        // deletable = true,
        fieldName = column.dataIndex.toString(), groupId = null, } = column.rendererAttrs || {};
        const classList = `border rounded bg-gray-100 hover:bg-gray-200 p-1 w-full cursor-pointer`;
        // Create input element dynamically
        const inputId = `${controlId}_file_input`;
        const inputElement = document.createElement('input');
        inputElement.id = inputId;
        // inputElement.name = controlName
        inputElement.type = 'file';
        inputElement.className = classList;
        inputElement.accept = `${fileType}/*`;
        inputElement.style.display = 'none';
        if (maxFileCount > 1)
            inputElement.multiple = true;
        (0, Attachment4OnUpload_1.addOnUploadListener)(inputElement, controlId, tableConfig, fieldName, groupId, this.dataLine);
        document.body.appendChild(inputElement);
        // Generate upload button HTML
        const uploadButton = !uploadable
            ? ''
            : `<button 
            class="text-xs border rounded bg-blue-500 hover:bg-blue-700 text-white px-2 py-1" 
            type="button" 
            onclick="document.getElementById('${inputId}').click()"
        ><i class="fa fa-upload"></i></button>`;
        return `<div class="flex items-center gap-0.5">
        <div id="${controlId}_thumbnail_div_view"></div>
        ${uploadButton}
        </div>`;
    }
}
exports.Attachment4Edit = Attachment4Edit;
//# sourceMappingURL=Attachment4Edit.js.map